<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreProductModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'store_products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

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
}
