<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesReturnModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'sales_returns';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice',
        'return_date',
        'sale_id',
        'type',
        'order_status',
        'payment_status',
        'tax_id',
        'store_id',
        'tax',
        'discount',
        'shipping',
        'payment_type',
        'total_amount',
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
        if (isset($data['data']['sale_id']) && empty($data['data']['sale_id']))
            $data['data']['sale_id'] = NULL;
        return $data;
    }

    protected function setTotalAmount(array $model)
    {
        if ($model && $model['data']) {
            $itemModel = new SalesReturnItemModel();
            $builder = $itemModel->builder();

            if ($model['singleton']) {
                $total = $builder->selectSum('subtotal', 'total')
                    ->where('sales_return_id', $model['data']->id)
                    ->get()
                    ->getRowObject()
                    ->total;
                $model['data']->total_amount = $total;
                $model['data']->items = $itemModel->where('sales_return_id', $model['data']->id)->findAll();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $total = $builder->selectSum('subtotal', 'total')
                        ->where('sales_return_id', $row->id)
                        ->get()
                        ->getRowObject()
                        ->total;
                    $model['data'][$key]->total_amount = $total;
                    $model['data'][$key]->items = $itemModel->where('sales_return_id', $row->id)->findAll();
                }
            }
        }
        return $model;
    }

    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $userModel = new UserModel();
            $saleModel = new SalesModel();

            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->sale = $saleModel->where('id', $model['data']->sale_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->sale = $saleModel->where('id', $row->sale_id)->first();
                }
            }
        }
        return $model;
    }

    public function getTotalAmount(): float
    {
        return (new SalesReturnItemModel())->getTotalAmount();
    }

    public function getTodayTotalAmount(): float
    {
        return (new SalesReturnItemModel())->getTodayTotalAmount();
    }

    public function getPaidAmount(): float
    {
        $total = $this->builder()->selectSum('paid', 'total')->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getDueAmount(): float
    {
        return (new SalesReturnItemModel())->getTotalAmount()
            - $this->getPaidAmount();
    }
}
