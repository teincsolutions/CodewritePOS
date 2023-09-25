<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

use function PHPUnit\Framework\isNull;

class SalesModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'sales';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice',
        'sales_date',
        'customer_id',
        'type',
        'order_status',
        'payment_status',
        'tax_id',
        'store_id',
        'tax',
        'discount',
        'shipping',
        'paid',
        'user_id'
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
    protected $afterFind      = ['setTotalAmount', 'setRelation'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setDefaultId(array $data)
    {
        if (isset($data['data']['customer_id']) && isNull($data['data']['customer_id']))
            $data['data']['customer_id'] = NULL;

        return $data;
    }

    protected function setTotalAmount(array $model)
    {
        if (isset($model['data'])) {
            $itemModel = new SalesItemModel();
            $builder = $itemModel->builder();

            if (isset($model['singleton']) && $model['singleton']) {
                $total = $builder->selectSum('subtotal', 'total')
                    ->where('sale_id', $model['data']->id)
                    ->get()
                    ->getRowObject()
                    ->total;
                $model['data']->total_amount = $total;
                $model['data']->items = $itemModel->where('sale_id', $model['data']->id)->findAll();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $total = $builder->selectSum('subtotal', 'total')
                        ->where('sale_id', $row->id)
                        ->get()
                        ->getRowObject()
                        ->total;
                    $model['data'][$key]->total_amount = $total;
                    $model['data'][$key]->items = $itemModel->where('sale_id', $row->id)->findAll();
                }
            }
        }
        return $model;
    }

    protected function setRelation($model)
    {
        if (isset($model['data'])) {
            $userModel = new UserModel();
            $cusModel = new CustomerModel();

            if (isset($model['singleton']) && $model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->customer = $cusModel->where('id', $model['data']->customer_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->customer = $cusModel->where('id', $row->customer_id)->first();
                }
            }
        }
        return $model;
    }
}
