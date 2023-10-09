<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductTransferItemModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_transfer_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'unit_price',
        'unit_cost',
        'product_transfer_id',
        'from_store_id',
        'to_store_id',
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

            if ($model['singleton']) {
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
