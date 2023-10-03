<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

use function PHPUnit\Framework\is_null;

class ProductModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'barcode',
        'sku',
        'brand_id',
        'category_id',
        'unit_cost',
        'unit_price',
        'tax_id',
        'discount',
        'unit_id',
        'description',
        'image_uri',
        'discontinued',
        'inventory',
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
    protected $afterFind      = ['setInstock', 'setRelation'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    protected function setDefaultId(array $data)
    {
        if (isset($data['data']['brand_id']) && empty($data['data']['brand_id']))
            $data['data']['brand_id'] = NULL;

        if (isset($data['data']['barcode']) && empty($data['data']['barcode']))
            $data['data']['barcode'] = NULL;

        if (isset($data['data']['tax_id']) && empty($data['data']['tax_id']))
            $data['data']['tax_id'] = NULL;

        return $data;
    }

    protected function setInstock($model)
    {
        if (isset($model['data']) && $model['data']) {
            $stockModel = new StockModel();
            $builder = $stockModel->builder();
            if ($model['singleton']) {
                if (isset($model['data']->id)) {
                    $model['data']->inventory = $stockModel->where('product_id',  $model['data']->id)->findAll();
                    $instock = $builder->selectSum('instock', 'total')
                        ->where('product_id',  $model['data']->id)
                        ->get()
                        ->getRowObject()
                        ->total;
                    $model['data']->instock = $instock ?? 0.00;
                }
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->inventory = $stockModel->where('product_id', $row->id)->findAll();
                    $instock = $builder->selectSum('instock', 'total')
                        ->where('product_id',  $row->id)
                        ->get()
                        ->getRowObject()
                        ->total;
                    $model['data'][$key]->instock = $instock ?? 0.00;
                }
            }
        }
        return $model;
    }
    protected function setRelation($model)
    {
        if ($model && $model['data']) {
            $userModel = new UserModel();
            $brandModel = new BrandModel();
            $catModel = new CategoryModel();
            $unitModel = new UnitModel();
            $taxModel = new TaxModel();
            if ($model['singleton']) {
                $model['data']->user = $userModel->where('id', $model['data']->user_id)->first();
                $model['data']->brand = $brandModel->where('id', $model['data']->brand_id)->first();
                $model['data']->category = $catModel->where('id', $model['data']->category_id)->first();
                $model['data']->unit = $unitModel->where('id', $model['data']->unit_id)->first();
                $model['data']->tax = $taxModel->where('id', $model['data']->tax_id)->first();
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->brand = $brandModel->where('id', $row->brand_id)->first();
                    $model['data'][$key]->category = $catModel->where('id', $row->category_id)->first();
                    $model['data'][$key]->unit = $unitModel->where('id', $row->unit_id)->first();
                    $model['data'][$key]->tax = $taxModel->where('id', $row->tax_id)->first();
                }
            }
        }
        return $model;
    }
}
