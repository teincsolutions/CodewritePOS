<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class QuoteModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'quotes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice',
        'quote_date',
        'customer_id',
        'type',
        'store_id',
        'tax',
        'discount',
        'shipping',
        'total_amount',
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
            $itemModel = new QuoteItemModel();
            $ledger = new CustomerLedgerModel();

            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->customer = $cusModel->where('id', $model['data']->customer_id)->first();
                $model['data']->items = $itemModel->where('quote_id', $model['data']->id)->findAll();
                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->customer = $cusModel->where('id', $row->customer_id)->first();
                    $model['data'][$key]->items = $itemModel->where('quote_id', $row->id)->findAll();
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
        $total += (new CustomerLedgerModel())->selectSum('debit', 'total')
            ->where('tdate', date('Y-m-d', time()))
            ->get()->getFirstRow()->total;

        // total paid by walk-in-customers
        $total += $this->builder()->selectSum('total_amount', 'total')
            ->where('quote_date', date('Y-m-d', time()))
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
}
