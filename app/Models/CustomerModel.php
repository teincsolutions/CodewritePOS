<?php

namespace App\Models;

use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;
use Config\Database;

class CustomerModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'type',
        'phone',
        'email',
        'status',
        'address',
        'discount',
        'user_id',
        'credit_limit',
        'credit_limit_days'
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
    protected $beforeInsert   = ['setDefault'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ['setRelation'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setDefault(array $data)
    {
        if (isset($data['data']['credit_limit']) && empty($data['data']['credit_limit']))
            $data['data']['credit_limit'] = NULL;

        if (isset($data['data']['credit_limit_days']) && empty($data['data']['credit_limit_days']))
            $data['data']['credit_limit_days'] = NULL;

        return $data;
    }

    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $userModel = new UserModel();
            $ledgerModel = new CustomerLedgerModel();

            if (isset($model['singleton']) && $model['singleton'] && $model['data']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                // balance
                $total = $ledgerModel->builder()
                    ->selectSum(new RawSql('(credit - debit)'), 'total')
                    ->where('customer_id', $model['data']->id)
                    ->get()
                    ->getRowObject()
                    ->total;
                $model['data']->balance = $total ? $total : 0.00;
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    // balance
                    $total = $ledgerModel->builder()
                        ->selectSum(new RawSql('(credit - debit)'), 'total')
                        ->where('customer_id', $row->id)
                        ->get()
                        ->getRowObject()
                        ->total;
                    $model['data'][$key]->balance = $total ? $total : 0.00;
                }
            }
        }
        return $model;
    }

    public function addInitialBalance($customer_id, $store_id, $amount, $date = null)
    {
        $salesModel = new SalesModel();
        $ledgerModel = new CustomerLedgerModel();
        $res = [
            'status' => false,
            'data' => null,
            'message' => null
        ];

        $customer = $this->where('id', $customer_id)->first();
        $this->db = Database::connect();

        if ($customer) {
            if (!auth()->user()->can('customer-ledgers.create'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to credit this record!"
                ]);

            try {
                $this->db->transException(true)->transStart();

                $lastItem = $salesModel->orderBy('id', 'desc')->first();
                $lastId = $lastItem ? $lastItem->id : 1;
                $data = [
                    'customer_id' => $customer_id,
                    'store_id' => $store_id,
                    'invoice' => substr(time() + $lastId, 0, 10),
                    'sales_date' => $date ?? date('Y-m-d'),
                    'total_amount' => $amount,
                    'type' => 'customer',
                    'order_status' => 'completed',
                    'user_id' => auth()->user()->id,
                    'payment_status' => $amount > 0 ? 'due' : 'paid'
                ];

                $salesModel->save($data);

                $data = [
                    'customer_id' => $customer_id,
                    'store_id' => $store_id,
                    'sale_id' => $salesModel->getInsertID(),
                    'ledger_type' => 'sales',
                    'tdate' => $date ?? date('Y-m-d'),
                    'debit' => $amount,
                    'credit' => 0,
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
