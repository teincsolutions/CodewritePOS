<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

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
}
