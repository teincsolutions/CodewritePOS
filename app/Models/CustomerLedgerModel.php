<?php

namespace App\Models;

use CodeIgniter\Model;


class CustomerLedgerModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'customer_ledgers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tdate',
        'sale_id',
        'customer_id',
        'sales_return_id',
        'debit',
        'credit',
        'user_id',
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
        if (isset($data['data']['customer_id']) && empty($data['data']['customer_id']))
            $data['data']['customer_id'] = NULL;

        return $data;
    }

    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $userModel = new UserModel();
            $saleModel = new SalesModel();
            $returnModel = new SalesReturnModel();
            $cusModel = new CustomerModel();

            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->sale = $saleModel->where('id', $model['data']->sale_id)->first();
                $model['data']->sales_return = $returnModel->where('id', $model['data']->sales_return_id)->first();
                $model['data']->customer = $cusModel->where('id', $model['data']->customer_id)->first();
            } else {
                $bal = 0;
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->sale = $saleModel->where('id', $row->sale_id)->first();
                    $model['data'][$key]->sales_return = $returnModel->where('id', $row->sales_return_id)->first();
                    $model['data'][$key]->customer = $cusModel->where('id', $row->customer_id)->first();
                    $bal += $model['data'][$key]->credit - $model['data'][$key]->debit;
                    $model['data'][$key]->balance = $bal;
                }
            }
        }
        return $model;
    }
}
