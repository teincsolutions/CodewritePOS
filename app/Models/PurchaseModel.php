<?php

namespace App\Models;

use CodeIgniter\Model;

use function PHPUnit\Framework\is_null;

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
                $model['data']->paid = $total ?? 0.00;
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->supplier = $supModel->where('id', $row->supplier_id)->first();
                    $model['data'][$key]->items = $itemModel->where('purchase_id', $row->id)->findAll();
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

    public function getTotalAmount(): float
    {
        $total = $this->builder()->selectSum('total_amount', 'total')->get()->getFirstRow()->total;
        return $total ? $total : 0.00;
    }

    public function getTodayTotalAmount(): float
    {
        $total = $this->builder()
            ->selectSum('total_amount', 'total')
            ->where('purchase_date', date('Y-m-d', time()))
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
        return $this->getTotalAmount()
            - $this->getPaidAmount();
    }
}
