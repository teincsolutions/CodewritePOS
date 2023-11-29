<?php

namespace App\Models;

use CodeIgniter\Database\MySQLi\Builder;
use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;


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
        if (isset($data['data']['supplier_id']) && empty($data['data']['supplier_id']))
            $data['data']['supplier_id'] = NULL;
        return $data;
    }
    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $userModel = new UserModel();
            $supModel = new SupplierModel();
            $storeModel = new StoreModel();
            $itemModel = new PurchaseItemModel();
            $ledger = new SupplierLedgerModel();

            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->supplier = $supModel->where('id', $model['data']->supplier_id)->first();
                $model['data']->items = $itemModel->where('purchase_id', $model['data']->id)->findAll();
                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
                $total = $ledger->builder()->selectSum('debit', 'total')
                    ->where('purchase_id', $model['data']->id)
                    ->get()
                    ->getRowObject()->total;
                $model['data']->change = ($model['data']->paid > $model['data']->total_amount) ? abs($model['data']->total_amount - $model['data']->paid) : 0.00;
                $model['data']->paid = $total ?? 0.00;
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->supplier = $supModel->where('id', $row->supplier_id)->first();
                    $model['data'][$key]->items = $itemModel->where('purchase_items.purchase_id', $row->id)->findAll();
                    $model['data'][$key]->change = ($model['data'][$key]->paid >  $model['data'][$key]->total_amount) ?  abs($model['data'][$key]->total_amount -  $model['data'][$key]->paid) : 0.00;
                    $total = $ledger->builder()->selectSum('debit', 'total')
                        ->where('purchase_id', $row->id)
                        ->get()
                        ->getRowObject()->total;
                    $model['data'][$key]->paid = $total ?? 0.00;

                    $model['data'][$key]->store = $storeModel->where('id', $row->store_id)->first();
                }
            }
        }
        return $model;
    }

    public function getItemsWithReturnItems($purchaseId)
    {
        $itemModel = new PurchaseItemModel();
        $itemModel->select('purchase_items.*, sum(purchase_returns_items.unit_price) as rtn_unit_price,sum(purchase_returns_items.discount) as rtn_discount,  sum(purchase_returns_items.qty) as rtn_qty,  sum(purchase_returns_items.subtotal) as rtn_subtotal');
        $itemModel->join('purchase_returns_items', 'purchase_returns_items.purchase_item_id=purchase_items.id', 'left');
        $itemModel->where('purchase_items.purchase_id', $purchaseId);
        $itemModel->groupBy('purchase_items.id');
        return $itemModel->findAll();
    }

    public function hasReturns($purchaseId): bool
    {
        $returnModel = new PurchaseReturnModel();
        return  $returnModel->where('purchase_id', $purchaseId)->countAllResults() > 0;
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
        $today = date('Y-m-d', time());

        $builder = $this->builder();
        $builder->selectSum('total_amount', 'total')
            ->where('purchase_date', $today)
            ->where('order_status', 'completed');
        if ($storeId) $builder->where('store_id', $storeId);


        $total = $builder->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getPaidAmount(): float
    {
        $total = $this->builder()->selectSum('paid', 'total')
            ->where('order_status', 'completed')->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getDueAmount($storeId = null): float
    {
        $builder = $this->builder();

        $builder->selectSum(new RawSql('(total_amount - paid)'), 'total')
            ->where('payment_status', 'due')
            ->where('order_status', 'completed');
        if ($storeId) $builder->where('store_id', $storeId);

        $total = $builder->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }


    public function updatePaymentStatus($id)
    {
        if (!$id) return;
        $purchaseModel = new PurchaseModel();
        $purchase = $purchaseModel->where('id', $id)->first();
        if ($purchase) {
            return $purchaseModel->save([
                'id' => $id,
                'payment_status' => ($purchase->total_amount - $purchase->paid) > 0 ? 'due' : 'paid'
            ]);
        }
        return false;
    }

    public function getDailyReport($where = [], $from = null, $to = null): Builder
    {
        $builder = $this->builder();

        if ($from && $to) {
            $from  = $builder->db->escape($from);
            $to = $builder->db->escape($to);
            $builder->where("purchase_date BETWEEN $from AND $to");
        }
        $builder->select(
            "purchase_date,
            SUM(CASE WHEN supplier_ledgers.payment_type = 'cash' THEN (ifnull(supplier_ledgers.debit,0)) ELSE 0 END) AS cash_purchases,
            SUM(CASE WHEN supplier_ledgers.payment_type = 'momo' THEN (ifnull(supplier_ledgers.debit,0)) ELSE 0 END) AS momo_purchases,
            SUM(total_amount) AS total_purchases,
            SUM(CASE WHEN purchases.payment_status = 'due' THEN (total_amount - paid) ELSE 0 END) AS due_purchases",
            false
        )->join('supplier_ledgers', 'supplier_ledgers.purchase_id=purchases.id', 'left')
            ->where($where)
            ->where('order_status', 'completed')
            ->groupBy('purchase_date');

        return $builder;
    }
}
