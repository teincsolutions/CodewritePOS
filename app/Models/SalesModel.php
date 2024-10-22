<?php

namespace App\Models;

use CodeIgniter\Database\MySQLi\Builder;
use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;
use DateTime;

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
        'store_id',
        // 'tax',
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
                $model['data']->items = $itemModel->where('sale_id', $model['data']->id)->findAll();
                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
                $model['data']->change = ($model['data']->paid > $model['data']->total_amount) ? abs($model['data']->total_amount - $model['data']->paid) : 0.00;
                if ($model['data']->type === 'customer') {
                    $total = $ledger->builder()->selectSum('credit', 'total')
                        ->where('sale_id', $model['data']->id)
                        ->get()
                        ->getRowObject()->total;
                    $model['data']->checkoutPaid = $model['data']->paid;
                    $model['data']->paid = $total ?? 0.00;
                }
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->customer = $cusModel->where('id', $row->customer_id)->first();
                    //$model['data'][$key]->items = $itemModel->where('sale_id', $row->id)->findAll();
                    $model['data'][$key]->change = ($model['data'][$key]->paid >  $model['data'][$key]->total_amount) ?  abs($model['data'][$key]->total_amount -  $model['data'][$key]->paid) : 0.00;
                    if ($model['data'][$key]->type === 'customer') {
                        $total = $ledger->builder()->selectSum('credit', 'total')
                            ->where('sale_id', $row->id)
                            ->get()
                            ->getRowObject()->total;
                        $model['data']->checkoutPaid = $model['data']->paid;
                        $model['data'][$key]->paid = $total ?? 0.00;
                    }
                    $model['data'][$key]->store = $storeModel->where('id', $row->store_id)->first();
                }
            }
        }
        return $model;
    }

    public function getItemsWithReturnItems($saleId)
    {
        $itemModel = new SalesItemModel();
        $itemModel->select('sales_items.*, sum(sales_returns_items.unit_price) as rtn_unit_price,sum(sales_returns_items.discount) as rtn_discount,  sum(sales_returns_items.qty) as rtn_qty,  sum(sales_returns_items.subtotal) as rtn_subtotal');
        $itemModel->join('sales_returns_items', 'sales_returns_items.sale_item_id=sales_items.id', 'left');
        $itemModel->where('sales_items.sale_id', $saleId);
        $itemModel->groupBy('sales_items.id');
        return $itemModel->findAll();
    }

    public function hasReturns($saleId): bool
    {
        $returnModel = new SalesReturnModel();
        return  $returnModel->where('sale_id', $saleId)->countAllResults() > 0;
    }

    public function getTotalAmount($storeId = null): float
    {
        $builder = $this->builder();
        $builder->selectSum('total_amount', 'total')
            ->where('order_status', 'completed');
        if ($storeId) $builder->where('store_id', $storeId);

        $total =  $builder->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getTodayTotalAmount($storeId = null): float
    {
        $builder =  (new CustomerLedgerModel())->builder();
        // total paid by customers
        $builder->join('sales', 'sales.id=customer_ledgers.sale_id')
            ->selectSum('debit', 'total')
            ->where('sales.sales_date', date('Y-m-d', time()));
        if ($storeId) $builder->where('sales.store_id', $storeId);

        $total = $builder->get()->getFirstRow()->total;

        $builder =  $this->builder();

        // total paid by walk-in-customers
        $builder->selectSum('total_amount', 'total')
            ->where('sales_date', date('Y-m-d', time()))
            ->where('type', 'walk-in-customer')
            ->where('order_status', 'completed');
        if ($storeId) $builder->where('store_id', $storeId);

        $total += $builder->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getTodayTotalAmount2(): float
    {
        // total paid by walk-in-customers
        $total = $this->builder()->selectSum('total_amount', 'total')
            ->where('sales_date', date('Y-m-d', time()))
            ->where('type', 'walk-in-customer')
            ->where('order_status', 'completed')
            ->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getPaidAmount(): float
    {
        // total paid by customers
        $total = (new CustomerLedgerModel())->selectSum('credit', 'total')->get()->getFirstRow()->total;
        // total paid by walk-in-customers
        $total = ($total ?? 0) + $this->builder()->selectSum('total_amount', 'total')
            ->where('order_status', 'completed')
            ->where('type', 'walk-in-customer')->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getDueAmount($storeId = null): float
    {
        $builder = $this->builder();
        $builder->selectSum(new RawSql('(debit - credit)'), 'total')
            ->join('customer_ledgers', 'customer_ledgers.sale_id=sales.id')
            ->where('order_status', 'completed')
            ->where('payment_status', 'due');

        if ($storeId) $builder->where('sales.store_id', $storeId);

        $total =  $builder->get()->getFirstRow()->total;
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

    public function getDailyReport($where = [], $from = null, $to = null): Builder
    {
        $builder = $this->builder();

        if ($from && $to) {
            $from  = $builder->db->escape($from);
            $to = $builder->db->escape($to);
            $builder->where("sales_date BETWEEN $from AND $to");
        }

        $queryDebtPayment = "SELECT SUM(credit) FROM customer_ledgers INNER JOIN sales ON sales.id=customer_ledgers.sale_id WHERE sales.store_closing_id IS NOT NULL AND tdate=d1 AND customer_ledgers.store_id=s1 AND ledger_type='sales'";
        $queryDebtPaymentCash = "SELECT SUM(credit) FROM customer_ledgers INNER JOIN sales ON sales.id=customer_ledgers.sale_id WHERE sales.store_closing_id IS NOT NULL AND tdate=d1 AND customer_ledgers.store_id=s1 AND customer_ledgers.payment_type='cash' AND ledger_type='sales'";
        $queryDebtPaymentMoMo = "SELECT SUM(credit) FROM customer_ledgers INNER JOIN sales ON sales.id=customer_ledgers.sale_id WHERE sales.store_closing_id IS NOT NULL AND tdate=d1 AND customer_ledgers.store_id=s1 AND customer_ledgers.payment_type='momo' AND ledger_type='sales'";

        $queryCusReturnPayment = "SELECT SUM(credit) FROM customer_ledgers INNER JOIN sales_returns ON sales_returns.id=customer_ledgers.sales_return_id WHERE tdate=d1 AND customer_ledgers.store_id=s1";
        $queryWalkinReturnPayment = "SELECT SUM(sales_returns.paid) FROM sales_returns INNER JOIN sales ON sales.id=sales_returns.sale_id WHERE return_date=d1 AND sales_returns.store_id=s1 AND type='walk-in-customer'";
        $queryTotalReturns = "SELECT SUM(sales_returns.paid) FROM sales_returns WHERE  return_date=d1 AND customer_ledgers.store_id=s1";

        $builder->select(
            "sales_date,
            sales_date as d1,
            sales.store_id,
            sales.store_id as s1,
            ($queryDebtPayment) as total_debt_paid,
            ($queryDebtPaymentCash) as cash_debt_paid,
            ($queryDebtPaymentMoMo) as momo_debt_paid,
            ifnull(($queryCusReturnPayment),0) as return_debt_paid,
            ifnull(($queryWalkinReturnPayment),0) as return_paid,
            ifnull(($queryTotalReturns),0) as total_returns,
            SUM((CASE WHEN customer_ledgers.payment_type = 'cash' AND type = 'customer' THEN (ifnull(customer_ledgers.credit,0)) ELSE 0 END)+(CASE WHEN sales.payment_type = 'cash' AND type = 'walk-in-customer' THEN paid ELSE 0 END)) AS total_cash_sales,
            SUM(CASE WHEN customer_ledgers.payment_type = 'cash' AND type = 'customer' THEN (ifnull(customer_ledgers.credit,0)) ELSE 0 END) AS customer_cash_sales,
            SUM(CASE WHEN sales.payment_type = 'cash' AND type = 'walk-in-customer' THEN (paid) ELSE 0 END) AS cash_sales,
            SUM((CASE WHEN customer_ledgers.payment_type = 'momo' THEN (ifnull(customer_ledgers.credit,0)) ELSE 0 END)+(CASE WHEN sales.payment_type = 'momo' AND type = 'walk-in-customer' THEN (paid) ELSE 0 END)) AS total_momo_sales,
            SUM(CASE WHEN customer_ledgers.payment_type = 'momo' AND type = 'customer' THEN (ifnull(customer_ledgers.credit,0)) ELSE 0 END) AS customer_momo_sales,
            SUM(CASE WHEN sales.payment_type = 'momo' AND type = 'walk-in-customer' THEN (paid) ELSE 0 END) AS momo_sales,
            SUM(total_amount) AS total_sales,
            SUM(CASE WHEN sales.payment_status = 'due' AND type = 'customer' THEN (total_amount - paid) ELSE 0 END) AS due_sales",
            false
        )->join('customer_ledgers', 'customer_ledgers.sale_id=sales.id', 'left')
            ->where($where)
            ->where('order_status', 'completed')
            ->groupBy('sales_date');

        return $builder;
    }

    public function getOverdueReport($where = [], $from = null, $to = null): Builder
    {
        $builder = $this->builder();

        if ($from && $to) {
            $from  = $builder->db->escape($from);
            $to = $builder->db->escape($to);
            $builder->where("sales_date BETWEEN $from AND $to");
        }

        $queryMaxSales = "SELECT MAX(sales.sales_date) FROM sales WHERE sales.customer_id=customers.id AND sales.order_status='completed' AND sales.payment_status = 'due'";

        $builder->select(
            "DATE_FORMAT(MAX(customer_ledgers.tdate), '%D %M,%Y') as tdate,
            MAX(sales.sales_date) as last_sale_date,
            sales.store_id,
            sales.customer_id,
            SUM((debit-credit)) AS total_due,
            DATEDIFF(DATE(NOW()),($queryMaxSales)) as days_left",
            false
        )->join('customer_ledgers', 'customer_ledgers.sale_id=sales.id')
            ->join('customers', 'customers.id=sales.customer_id')
            ->where($where)
            ->where('order_status', 'completed')
            ->where('payment_status', 'due')
            ->where("DATEDIFF(DATE(NOW()),($queryMaxSales)) >= customers.credit_limit_days")
            ->groupBy('sales.customer_id');

        return $builder;
    }

    public function customerLatestDays($customerId, array $cond = null)
    {
        $where = ['customer_id' => $customerId];

        if ($cond == null) $where = array_merge($where, [
            'order_status' => 'completed',
            'payment_status' => 'due'
        ]);
        else array_merge($where, $cond);

        $sale = $this->where($where)->orderBy('id', 'asc')->first();
        if ($sale) return (new DateTime($sale->sales_date))->diff(new DateTime())->days;
        return null;
    }

    public function getTodayProfit($storeId = null): float
    {
        return
            $this->builder()
            ->join('sales_items', 'sales_items.sale_id=sales.id')
            ->selectSum('((unit_price - sales_items.discount - unit_cost)*qty)', 'total')
            ->where('sales_date', date('Y-m-d', time()))
            ->where('sales.store_id', $storeId)
            ->get()
            ->getRowObject()
            ->total ?? 0.00;
    }
    public function getTodayDueAmount($storeId = null): float
    {
        $builder = $this->builder();
        $builder->selectSum(new RawSql('(debit - credit)'), 'total')
            ->join('customer_ledgers', 'customer_ledgers.sale_id=sales.id')
            ->where('order_status', 'completed')
            ->where('sales_date', date('Y-m-d', time()))
            ->where('payment_status', 'due');

        if ($storeId) $builder->where('sales.store_id', $storeId);

        $total =  $builder->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }
}
