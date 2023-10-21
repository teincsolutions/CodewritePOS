<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreProductModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'store_products';
    protected $primaryKey       = 'pro';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'store_id',
        'unit_price',
        'unit_cost',
        'unit_qty',
        'min_qty',
        'unit_ws_price',
        'discontinued'
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
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ['setRelation'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setDefaultId(array $data)
    {
        if (isset($data['data']['min_qty']) && empty($data['data']['min_qty']))
            $data['data']['min_qty'] = 10;

        if (isset($data['data']['unit_price']) && empty($data['data']['unit_price']))
            $data['data']['unit_price'] = 0.00;
        
        if (isset($data['data']['unit_ws_price']) && empty($data['data']['unit_ws_price']))
            $data['data']['unit_ws_price'] = $data['data']['unit_price'];

        return $data;
    }

    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $storeModel = new StoreModel();

            if ($model['singleton']) {
                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->store = $storeModel->where('id', $row->store_id)->first();
                }
            }
        }
        return $model;
    }

    public function getCost($productId, $storeId): float
    {
        $result = $this->where([
            'product_id' => $productId,
            'store_id' => $storeId
        ])
            ->first()
            ->unit_cost;

        return $result ?? 0.00;
    }

    public function getPrice($productId, $storeId): float
    {
        $result = $this->where([
            'product_id' => $productId,
            'store_id' => $storeId
        ])
            ->first()
            ->unit_price;

        return $result ?? 0.00;
    }

    public function getMinQty($productId, $storeId): float
    {
        $result = $this->where([
            'product_id' => $productId,
            'store_id' => $storeId
        ])
            ->first()
            ->min_qty;

        return $result ?? 0.00;
    }

    public function getWSPrice($productId, $storeId): float
    {
        $result = $this->where([
            'product_id' => $productId,
            'store_id' => $storeId
        ])
            ->first()
            ->unit_ws_price;

        return $result ?? 0.00;
    }

    public function getDiscount($productId, $storeId): float
    {
        $result = $this->where([
            'product_id' => $productId,
            'store_id' => $storeId
        ])
            ->first()
            ->discount;

        return $result ?? 0.00;
    }
    
    public function getDiscontinued($productId, $storeId) {
        $result = $this->where([
            'product_id' => $productId,
            'store_id' => $storeId
        ])
            ->first()
            ->discontinued;

        return $result ?? 0;
    }
}
