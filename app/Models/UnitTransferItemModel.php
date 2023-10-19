<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitTransferItemModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'unit_transfer_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'from_product_id',
        'to_product_id',
        'unit_transfer_id',
        'from_unit_qty',
        'to_unit_qty'
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
            $prodModel = new ProductModel();
            $unitModel = new UnitModel();

            if ($model['singleton']) {
                $model['data']->fromProduct = $prodModel->where('id', $model['data']->from_product_id)->first();
                $model['data']->toProduct = $prodModel->where('id', $model['data']->to_product_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->fromProduct = $prodModel->where('id', $row->from_product_id)->first();
                    $model['data'][$key]->toProduct = $prodModel->where('id', $row->to_product_id)->first();
                }
            }
        }
        return $model;
    }

    public function getTotalAmount(): float
    {
        $result = $this->selectSum('subtotal', 'total')->first();
        return $result->total ? $result->total : 0.00;
    }

    public function getTodayTotalAmount(): float
    {
        $result = $this->selectSum('subtotal', 'total')
            ->join('unit_transfers', 'unit_transfers.id=unit_transfer_items.unit_transfer_id')
            ->where('transfer_date', date('Y-m-d', time()))
            ->first();
        return $result->total ? $result->total : 0.00;
    }
}
