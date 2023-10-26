<?php

namespace App\Models;

use CodeIgniter\Model;

class ContainerStockAdjustmentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'container_stock_adjustments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'adj_date',
        'invoice',
        'store_id',
        'total_amount',
        'user_id',
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
            $storeModel = new StoreModel();
            $userModel = new UserModel();
            $itemModel = new ContainerAdjustmentItemModel();

            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
                $model['data']->items = $itemModel->where('container_adjustment_id', $model['data']->id)->findAll();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->store = $storeModel->where('id', $row->store_id)->first();
                    $model['data'][$key]->items = $itemModel->where('container_adjustment_id', $row->id)->findAll();
                }
            }
        }
        return $model;
    }
}
