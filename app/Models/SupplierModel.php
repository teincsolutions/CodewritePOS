<?php

namespace App\Models;

use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;
use Config\Database;

class SupplierModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'suppliers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'phone',
        'email',
        'status',
        'address',
        'note',
        'discount',
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
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ['setRelation'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $userModel = new UserModel();
            $ledgerModel = new SupplierLedgerModel();

            if (isset($model['singleton']) && $model['singleton'] && $model['data']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                // balance
                $total = $ledgerModel->builder()
                    ->selectSum(new RawSql('(credit-debit)'), 'total')
                    ->where('supplier_id', $model['data']->id)
                    ->get()
                    ->getRowObject()
                    ->total;
                $model['data']->balance = $total ? $total : 0.00;
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    // balance
                    $total = $ledgerModel->builder()
                        ->selectSum(new RawSql('(credit-debit)'), 'total')
                        ->where('supplier_id', $row->id)
                        ->get()
                        ->getRowObject()
                        ->total;
                    $model['data'][$key]->balance = $total ? $total : 0.00;
                }
            }
        }
        return $model;
    }

    public function addInitialBalance($supplier_id, $store_id, $amount, $date = null)
    {
        $purchaseModel = new PurchaseModel();
        $ledgerModel = new SupplierLedgerModel();
        $res = [
            'status' => false,
            'data' => null,
            'message' => null
        ];

        $supplier = $this->where('id', $supplier_id)->first();
        $this->db = Database::connect();

        if ($supplier) {
            if (!auth()->user()->can('supplier-ledgers.edit-credit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to credit this record!"
                ]);

            try {
                $this->db->transException(true)->transStart();

                $lastItem = $purchaseModel->where('purchase_date', $date)->orderBy('id', 'desc')->first();
                $invoice = $lastItem ? intval($lastItem->invoice) + 1 : date('ymd', strtotime($date)) . str_pad('1', 4, '0', STR_PAD_LEFT);
        
                $data = [
                    'supplier_id' => $supplier_id,
                    'store_id' => $store_id,
                    'invoice' => $invoice,
                    'purchase_date' => $date ?? date('Y-m-d'),
                    'total_amount' => $amount,
                    'order_status' => 'completed',
                    'user_id' => auth()->user()->id,
                    'payment_status' => $amount > 0 ? 'due' : 'paid'
                ];

                $purchaseModel->save($data);

                $data = [
                    'supplier_id' => $supplier_id,
                    'store_id' => $store_id,
                    'purchase_id' => $purchaseModel->getInsertID(),
                    'ledger_type' => 'purchases',
                    'tdate' => $date ?? date('Y-m-d'),
                    'debit' => 0,
                    'credit' => $amount,
                    'user_id' => auth()->user()->id,
                ];
                $ledgerModel->save($data);

                if ($this->db->transComplete()) {
                    $res = array_merge($res, [
                        'status' => true,
                        'message' => "Balance updated successfully!",
                    ]);
                }
            } catch (DatabaseException $e) {
                $res = array_merge($res, [
                    'message' => $e->getMessage(),
                ]);
            }
        }
        return $res;
    }
}
