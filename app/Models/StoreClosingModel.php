<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreClosingModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'store_closings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'opening_balance',
        'customer_payment',
        'supplier_payment',
        'sale_total',
        'sale_return_total',
        'purchase_return_total',
        'stock_adjust_balance',
        'product_transfer_balance',
        'expense_total',
        'cash_in_hand',
        'cashup',
        'closing_balance',
        'user_id',
        'store_id'
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
            $storeModel = new StoreModel();
            
            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->store = $storeModel->where('id', $row->store_id)->first();
                }
            }
        }
        return $model;
    }
}
