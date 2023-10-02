<?php

namespace App\Models;

use CodeIgniter\Model;

use function PHPUnit\Framework\isNull;

class PurchaseModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'purchases';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice',
        'purchase_date',
        'supplier_id',
        'store_id',
        'order_status',
        'payment_status',
        'tax_id',
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
        if (isset($data['data']['supplier_id']) && isNull($data['data']['supplier_id']))
            $data['data']['supplier_id'] = NULL;
        return $data;
    }

    protected function setTotalAmount(array $model)
    {
        if ($model && $model['data']) {
            $itemModel = new PurchaseItemModel();
            $builder = $itemModel->builder();

            if ($model['singleton']) {
                $total = $builder->selectSum('subtotal', 'total')
                    ->where('purchase_id', $model['data']->id)
                    ->get()
                    ->getRowObject()
                    ->total;
                $model['data']->total_amount = $total;
                $model['data']->items = $itemModel->where('purchase_id', $model['data']->id)->findAll();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $total = $builder->selectSum('subtotal', 'total')
                        ->where('purchase_id', $row->id)
                        ->get()
                        ->getRowObject()
                        ->total;
                    $model['data'][$key]->total_amount = $total;
                    $model['data'][$key]->items = $itemModel->where('purchase_id', $row->id)->findAll();
                }
            }
        }
        return $model;
    }

    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $userModel = new UserModel();
            $cusModel = new SupplierModel();

            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->supplier = $cusModel->where('id', $model['data']->supplier_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->supplier = $cusModel->where('id', $row->supplier_id)->first();
                }
            }
        }
        return $model;
    }

    public function getTotalAmount(): float
    {
        return (new PurchaseItemModel())->getTotalAmount();
    }
    public function getTodayTotalAmount(): float
    {
        return (new PurchaseItemModel())->getTodayTotalAmount();
    }

    public function getPaidAmount(): float
    {
        $total = $this->builder()->selectSum('paid', 'total')->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getDueAmount(): float
    {
        return $this->getTotalAmount()
            - $this->getPaidAmount();
    }
}
