<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesReturnItemModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'sales_returns_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'unit_price',
        'sales_return_id',
        'store_id',
        'qty',
        'tax',
        'tax_id',
        'discount',
        'subtotal'
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
    protected $beforeInsert   = ['setDefaultId'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['setDefaultId'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ['setRelation'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setDefaultId(array $data)
    {
        if (isset($data['data']['tax_id']) && empty($data['data']['tax_id']))
            $data['data']['tax_id'] = NULL;
        return $data;
    }

    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $prodModel = new ProductModel();
            $storeModel = new StoreModel();

            if ($model['singleton']) {
                if (isset($model['data']->product_id))
                    $model['data']->product = $prodModel->where('id', $model['data']->product_id)->first();
                if (isset($model['data']->store_id))
                    $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->product = $prodModel->where('id', $row->product_id)->first();
                    $model['data'][$key]->store = $storeModel->where('id', $row->store_id)->first();
                }
            }
        }
        return $model;
    }

    public function getTotalAmount(): float
    {
        $result = $this->selectSum('subtotal', 'total')->first();
        return $result->total ? $result->total : 0.00;
    }

    public function getTodayTotalAmount(): float
    {
        $result = $this->selectSum('subtotal', 'total')
            ->join('sales_returns', 'sales_returns.id=sales_returns_items.sales_return_id')
            ->where('return_date', date('Y-m-d', time()))
            ->first();
        return $result->total ? $result->total : 0.00;
    }
}
