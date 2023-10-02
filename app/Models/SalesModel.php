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
            $cusModel = new CustomerModel();
            $storeModel = new StoreModel();
            $itemModel = new SalesItemModel();
            $ledger = new CustomerLedgerModel();

            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->customer = $cusModel->where('id', $model['data']->customer_id)->first();
                $model['data']->items = $itemModel->where('sale_id', $model['data']->id)->findAll();
                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
                if ($model['data']->type === 'customer') {
                    $total = $ledger->builder()->selectSum('credit', 'total')
                        ->where('sale_id', $model['data']->id)
                        ->get()
                        ->getRowObject()->total;
                    $model['data']->paid = $total ?? 0.00;
                }
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->customer = $cusModel->where('id', $row->customer_id)->first();
                    $model['data'][$key]->items = $itemModel->where('sale_id', $row->id)->findAll();
                    if ($model['data'][$key]->type === 'customer') {
                        $total = $ledger->builder()->selectSum('credit', 'total')
                            ->where('sale_id', $row->id)
                            ->get()
                            ->getRowObject()->total;
                        $model['data'][$key]->paid = $total ?? 0.00;
                    }
                    $model['data'][$key]->store = $storeModel->where('id', $row->store_id)->first();
                }
            }
        }
        return $model;
    }

    public function getTotalAmount(): float
    {
        $total = $this->builder()->selectSum('total_amount', 'total')->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getTodayTotalAmount(): float
    {
        $total = $this->builder()
            ->selectSum('total_amount', 'total')
            ->where('sales_date', date('Y-m-d', time()))
            ->get()
            ->getFirstRow()
            ->total;
        return $total ? $total : 0.00;
    }

    public function getPaidAmount(): float
    {
        $total = $this->builder()->selectSum('paid', 'total')->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getDueAmount(): float
    {
        return (new SalesItemModel())->getTotalAmount()
            - $this->getPaidAmount();
    }
}
