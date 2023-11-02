<?php

namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'stocks';
    protected $primaryKey       = 'product_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'store_id',
        'instock'
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
            $storeModel = new StoreModel();
            $productModel = new ProductModel();
            $unitModel = new UnitModel();
            $brandModel = new BrandModel();
            $categoryModel = new CategoryModel();

            if ($model['singleton']) {
                $model['data']->store = $storeModel->where('id', $model['data']->store_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->store = $storeModel->where('id', $row->store_id)->first();
                    $model['data'][$key]->product = $productModel->builder()->where('id', $row->product_id)->get()->getRowObject();
                    $model['data'][$key]->unit = $unitModel->builder()->where('id', $model['data'][$key]->product->unit_id)->get()->getRowObject();
                    $model['data'][$key]->brand = $brandModel->builder()->where('id', $model['data'][$key]->product->brand_id)->get()->getRowObject();
                    $model['data'][$key]->category = $categoryModel->builder()->where('id', $model['data'][$key]->product->category_id)->get()->getRowObject();
                }
            }
        }
        return $model;
    }

    public function getShortTotal(): float
    {
        return
            $this->builder()
            ->selectCount('*', 'total')
            ->join('products', 'products.id=stocks.product_id')
            ->where('stocks.instock <=', 'products.min_qty', false)
            ->where('stocks.instock >=', 0)
            ->get()
            ->getRowObject()
            ->total;
    }

    public function getInstockTotal(): float
    {
        return
            $this->builder()
            ->selectCount("*", 'total')
            ->where('instock >', 0, false)
            ->get()
            ->getRowObject()
            ->total;
    }

    public function getOutOfStockTotal(): float
    {
        $model = new ProductModel();

        return
            $model->builder()
            ->selectCount("*", 'total')
            ->where('ifnull((SELECT sum(ifnull(stocks.instock,0)) from stocks where stocks.product_id=products.id),0) <=', 0, false)
            ->get()
            ->getRowObject()
            ->total;
    }
}
