<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;


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
                $model['data']->items = $itemModel->select('sales_items.id,sales_items.sale_id, sales_items.product_id, sales_items.store_id,sales_items.subtotal, sales_items.tax_id,sales_items.unit_price,sales_items.unit_cost, (SUM(sales_items.qty)-SUM(ifnull(sales_returns_items.qty,0))) as qty, sales_items.tax, sales_items.discount,(SUM(sales_items.qty)-SUM(ifnull(sales_returns_items.qty,0))) as max_qty, sales_items.id as sale_item_id')
                    ->where('sales_items.sale_id', $model['data']->id)
                    ->join('sales_returns', 'sales_returns.sale_id=sales_items.sale_id', 'left')
                    ->join('sales_returns_items', 'sales_returns_items.sales_return_id=sales_returns.id AND sales_returns_items.product_id=sales_items.product_id', 'left')
                    ->groupBy('sales_items.product_id')
                    ->findAll();

                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
                $model['data']->change = ($model['data']->paid > $model['data']->total_amount) ? abs($model['data']->total_amount - $model['data']->paid) : 0.00;
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
                    $model['data'][$key]->items = $itemModel->select('sales_items.id,sales_items.sale_id,sales_items.product_id, sales_items.store_id,sales_items.subtotal, sales_items.tax_id,sales_items.unit_price,sales_items.unit_cost, (SUM(sales_items.qty)-SUM(ifnull(sales_returns_items.qty,0))) as qty, sales_items.tax, sales_items.discount,(SUM(sales_items.qty)-SUM(ifnull(sales_returns_items.qty,0))) as max_qty, sales_items.id as sale_item_id')
                        ->where('sales_items.sale_id', $row->id)
                        ->join('sales_returns', 'sales_returns.sale_id=sales_items.sale_id', 'left')
                        ->join('sales_returns_items', 'sales_returns_items.sales_return_id=sales_returns.id AND sales_returns_items.product_id=sales_items.product_id', 'left')
                        ->groupBy('sales_items.product_id')
                        ->findAll();
                    $model['data'][$key]->change = ($model['data'][$key]->paid >  $model['data'][$key]->total_amount) ?  abs($model['data'][$key]->total_amount -  $model['data'][$key]->paid) : 0.00;
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
        $total = 0;
        // total paid by customers
        $total += (new CustomerLedgerModel())
            ->join('sales', 'sales.id=customer_ledgers.sale_id')
            ->selectSum('debit', 'total')
            ->where('sales.sales_date', date('Y-m-d', time()))
            ->get()->getFirstRow()->total;

        // total paid by walk-in-customers
        $total += $this->builder()->selectSum('total_amount', 'total')
            ->where('sales_date', date('Y-m-d', time()))
            ->where('type', 'walk-in-customer')
            ->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getTodayTotalAmount2(): float
    {
        // total paid by walk-in-customers
        $total = $this->builder()->selectSum('total_amount', 'total')
            ->where('sales_date', date('Y-m-d', time()))
            ->where('type', 'walk-in-customer')
            ->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getPaidAmount(): float
    {
        // total paid by customers
        $total = (new CustomerLedgerModel())->selectSum('credit', 'total')->get()->getFirstRow()->total;
        // total paid by walk-in-customers
        $total = ($total ?? 0) + $this->builder()->selectSum('paid', 'total')->where('type', 'walk-in-customer')->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getDueAmount(): float
    {
        $total = $this->builder()
            ->selectSum(new RawSql('(total_amount - paid)'), 'total')->where('payment_status', 'due')
            ->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function updatePaymentStatus($id)
    {
        if (!$id) return;
        $saleModel = new SalesModel();
        $sale = $saleModel->where('id', $id)->first();
        if ($sale) {
            return $saleModel->save([
                'id' => $id,
                'payment_status' => ($sale->total_amount - $sale->paid) > 0 ? 'due' : 'paid'
            ]);
        }
        return false;
    }
}
