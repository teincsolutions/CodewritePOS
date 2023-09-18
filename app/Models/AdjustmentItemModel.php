<?php

namespace App\Models;

use CodeIgniter\Model;

class AdjustmentItemModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'adjustments_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'store_id',
        'adjustment_id',
        'product_id',
        'unit_cost',
        'instock_qty',
        'qty',
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
            $prodModel = new ProductModel();
            $storeModel = new StoreModel();

            if ($model['singleton']) {
                if (isset($model['data']->product_id))
                    $model['data']->product = $prodModel->where('id', $model['data']->product_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->product = $prodModel->where('id', $row->product_id)->first();
                }
            }
        }
        return $model;
    }
}
