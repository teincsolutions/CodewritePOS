<?php

namespace App\Models;

use CodeIgniter\Model;

class ContainerAdjustmentItemModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'container_adjustment_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'store_id',
        'container_adjustment_id',
        'container_id',
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
            $prodModel = new ContainerModel();
            $storeModel = new StoreModel();

            if ($model['singleton']) {
                if (isset($model['data']->container_id))
                    $model['data']->container = $prodModel->where('id', $model['data']->container_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->container = $prodModel->where('id', $row->container_id)->first();
                }
            }
        }
        return $model;
    }
}
