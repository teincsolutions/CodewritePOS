<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreLedgerModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'store_ledgers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tdate',
        'store_closing_id',
        'store_id',
        'debit',
        'credit',
        'user_id',
        'ledger_type'
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

            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
            } else {
                $bal = 0;

                foreach (array_reverse($model['data'], true) as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $bal +=  $model['data'][$key]->credit - $model['data'][$key]->debit;
                    $model['data'][$key]->balance = $bal;
                }
            }
        }
        return $model;
    }

}
