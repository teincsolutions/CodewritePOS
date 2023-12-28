<?php

namespace App\Models;

use CodeIgniter\Model;

class ContainerReceivingModel extends Model
{
    protected $table            = 'container_receivings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice',
        'return_date',
        'customer_id',
        'order_status',
        'store_id',
        'payment_type',
        'total_amount',
        'paid',
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
    protected $beforeInsert   = ['setDefault'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ['setRelation'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setDefault(array $data)
    {
        if (isset($data['data']['customer_id']) && empty($data['data']['customer_id']))
            $data['data']['customer_id'] = NULL;
        return $data;
    }

    
    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $userModel = new UserModel();
            $cusModel = new CustomerModel();
            $storeModel = new StoreModel();
            $itemModel = new SalesItemModel();
            $ledger = new CustomerLedgerModel();

            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->customer = $cusModel->where('id', $model['data']->customer_id)->first();
                $model['data']->items = $itemModel->where('container_receiving_id', $model['data']->id)->findAll();
                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->customer = $cusModel->where('id', $row->customer_id)->first();
                    $model['data'][$key]->store = $storeModel->where('id', $row->store_id)->first();
                }
            }
        }
        return $model;
    }

}
