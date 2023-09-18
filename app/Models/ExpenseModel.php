<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'expenses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'expense_date',
        'store_id',
        'expense_category_id',
        'expense_subcategory_id',
        'description',
        'user_id',
        'amount',
        'store_closing_id',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ['setRelation'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $userModel = new UserModel();
            $catModel = new ExpenseCategoryModel();
            $subCatModel = new ExpenseSubCategoryModel();
            $storeModel = new StoreModel();
            
            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->category = $catModel->where('id', $model['data']->expense_category_id)->first();
                $model['data']->subcategory = $subCatModel->where('id', $model['data']->expense_subcategory_id)->first();
                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->category = $catModel->where('id', $row->expense_category_id)->first();
                    $model['data'][$key]->subcategory = $subCatModel->where('id', $row->expense_subcategory_id)->first();
                    $model['data'][$key]->store = $storeModel->where('id', $row->store_id)->first();
                }
            }
        }
        return $model;
    }

    public function getTotalAmount(): float
    {
        $total = $this->builder()->selectSum('amount', 'total')->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getTodayTotalAmount(): float
    {
        // total paid
        $total = $this->builder()->selectSum('amount', 'total')
            ->where('expense_date', date('Y-m-d', time()))
            ->get()->getFirstRow()->total;
            
        return $total ? $total : 0.00;
    }
}
