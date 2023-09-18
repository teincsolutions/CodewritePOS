<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerContainerModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'customer_containers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'customer_id',
        'store_id',
        'container_id',
        'instock',
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
            $storeModel = new StoreModel();
            $containerModel = new ContainerModel();

            if ($model['singleton']) {
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->container = $containerModel->where('id', $row->container_id)->first();
                    $model['data'][$key]->total_amount = $model['data'][$key]->container->unit_price * $model['data'][$key]->instock;
                }
            }
        }
        return $model;
    }
}
