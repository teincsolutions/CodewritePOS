<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class PurchaseReturnModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'purchase_returns';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice',
        'return_date',
        'purchase_id',
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
        if (isset($data['data']['purchase_id']) && empty($data['data']['purchase_id']))
            $data['data']['purchase_id'] = NULL;
        return $data;
    }
    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $userModel = new UserModel();
            $purchaseModel = new PurchaseModel();
            $itemModel = new PurchaseReturnItemModel();

            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->purchase = $purchaseModel->where('id', $model['data']->purchase_id)->first();
                $model['data']->items = $itemModel->where('purchase_return_id', $model['data']->id)->findAll();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->purchase = $purchaseModel->where('id', $row->purchase_id)->first();
                 //   $model['data'][$key]->items = $itemModel->where('purchase_return_id', $row->id)->findAll();
                }
            }
        }
        return $model;
    }

    public function getTotalAmount($storeId = null): float
    {
        $builder = $this->builder();
        $builder->selectSum('total_amount', 'total')
            ->where('order_status', 'completed');
        if ($storeId) $builder->where('store_id', $storeId);


        $total = $builder->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getTodayTotalAmount($storeId = null): float
    {
        $today  = date('Y-m-d', time());

        $builder = $this->builder();
        $builder->selectSum('total_amount', 'total')
            ->where('order_status', 'completed')
            ->where('return_date', $today);

        if ($storeId) $builder->where('store_id', $storeId);

        $total = $builder->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getPaidAmount(): float
    {
        // total paid by customers
        $total = (new SupplierLedgerModel())->selectSum('debit', 'total')->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getDueAmount(): float
    {
        $total = $this->builder()
            ->selectSum(new RawSql('(total_amount - paid)'), 'total')
            ->where('payment_status', 'due')
            ->where('order_status', 'completed')
            ->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }
}
