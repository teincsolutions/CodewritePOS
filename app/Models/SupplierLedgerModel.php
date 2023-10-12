<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierLedgerModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'supplier_ledgers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tdate',
        'purchase_id',
        'supplier_id',
        'purchase_return_id',
        'debit',
        'credit',
        'user_id',
        'payment_type',
        'ledger_type',
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
    protected $beforeDelete   = ['updateFields'];
    protected $afterDelete    = [];

    protected function setDefaultId(array $data)
    {
        if (isset($data['data']['supplier_id']) && empty($data['data']['supplier_id']))
            $data['data']['supplier_id'] = NULL;

        return $data;
    }

    protected function updateFields(array $model)
    {
        $ledgerModel = new SupplierLedgerModel();
        $purchaseModel = new PurchaseModel();

        foreach ($model['id'] as $key => $data) {
            $id = $data;
            $ledger = $ledgerModel->where('id', $id)->first();
            if ($ledger){
                $purchaseModel->save([
                    'id' => $ledger->purchase_id,
                    'payment_status' => 'due'
                ]);
            }
        }

        return $model;
    }

    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $userModel = new UserModel();
            $purchaseModel = new PurchaseModel();
            $returnModel = new PurchaseReturnModel();
            $supModel = new SupplierModel();

            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->purchase = $purchaseModel->where('id', $model['data']->purchase_id)->first();
                $model['data']->purchase_return = $returnModel->where('id', $model['data']->purchase_return_id)->first();
                $model['data']->supplier = $supModel->where('id', $model['data']->supplier_id)->first();
            } else {
                $bal = 0;
                foreach (array_reverse($model['data'], true) as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->purchase = $purchaseModel->where('id', $row->purchase_id)->first();
                    $model['data'][$key]->purchase_return = $returnModel->where('id', $row->purchase_return_id)->first();
                    $model['data'][$key]->supplier = $supModel->where('id', $row->supplier_id)->first();
                    $bal +=  $model['data'][$key]->credit - $model['data'][$key]->debit;
                    $model['data'][$key]->balance = $bal;
                }
            }
        }
        return $model;
    }

    public function getTodayTotalCredit(): float
    {
        $total = $this->builder()
            ->selectSum('credit', 'total')
            ->where('tdate', date('Y-m-d', time()))
            ->get()
            ->getFirstRow()
            ->total;
        return $total ? $total : 0.00;
    }

    public function getTodayTotalDebit(): float
    {
        $total = $this->builder()
            ->selectSum('debit', 'total')
            ->where('tdate', date('Y-m-d', time()))
            ->get()
            ->getFirstRow()
            ->total;
        return $total ? $total : 0.00;
    }
}
