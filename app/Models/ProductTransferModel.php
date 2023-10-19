<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductTransferModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'product_transfers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice',
        'from_store_id',
        'to_store_id',
        'order_status',
        'payment_status',
        'total_amount',
        'paid',
        'discount',
        'shipping',
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
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ['setRelation'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setDefaultId(array $data)
    {
        if (isset($data['data']['tax_id']) && empty($data['data']['tax_id']))
            $data['data']['tax_id'] = NULL;
        return $data;
    }

    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $storeModel = new StoreModel();
            $itemModel = new ProductTransferItemModel();
            $userModel = new UserModel();

            if ($model['singleton']) {
                $model['data']->fromStore = $storeModel->where('id', $model['data']->from_store_id)->first();
                $model['data']->toStore = $storeModel->where('id', $model['data']->to_store_id)->first();
                $model['data']->items = $itemModel->where('product_transfer_id', $model['data']->id)->findAll();
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->fromStore = $storeModel->where('id', $row->from_store_id)->first();
                    $model['data'][$key]->toStore = $storeModel->where('id', $row->to_store_id)->first();
                    $model['data'][$key]->items = $itemModel->where('product_transfer_id', $row->id)->findAll();
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
            ->join('product_transfers', 'product_transfers.id=product_transfer_items.product_transfer_id')
            ->where('transfer_date', date('Y-m-d', time()))
            ->first();
        return $result->total ? $result->total : 0.00;
    }
}
