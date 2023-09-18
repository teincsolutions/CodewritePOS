<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesItemModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'sales_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'unit_price',
        'unit_cost',
        'sale_id',
        'store_id',
        'qty',
       // 'tax',
        'discount',
        'subtotal'
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
            $storeModel = new StoreModel();
            $returnItemModel = new SalesReturnItemModel();

            if ($model['singleton']) {
                $model['data']->product = $prodModel->withDeleted()->where('id', $model['data']->product_id)->first();
                $model['data']->return_qty = $returnItemModel->builder()
                    ->selectSum('qty', 'total')
                    ->where('sale_item_id', $model['data']->id)
                    ->get()->getFirstRow()->total;

                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->product = $prodModel->withDeleted()->where('id', $row->product_id)->first();
                    $model['data'][$key]->return_qty = $returnItemModel->builder()
                        ->selectSum('qty', 'total')
                        ->where('sale_item_id', $row->id)
                        ->get()->getFirstRow()->total;
                    $model['data'][$key]->store = $storeModel->where('id', $row->store_id)->first();
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
            ->join('sales', 'sales.id=sales_items.sale_id')
            ->where('sales_date', date('Y-m-d', time()))
            ->first();
        return $result->total ? $result->total : 0.00;
    }
}
