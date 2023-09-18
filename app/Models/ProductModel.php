<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;


class ProductModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'barcode',
        'sku',
        'brand_id',
        'category_id',
        'unit_cost',
        'unit_price',
        'unit_ws_price',
        'min_qty',
        'unit_qty',
        'taxes',
        'discount',
        'unit_id',
        'description',
        'expiration',
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

        if (isset($data['data']['sku']) && empty($data['data']['sku']))
            $data['data']['sku'] = NULL;

        if (isset($data['data']['min_qty']) && empty($data['data']['min_qty']))
            $data['data']['min_qty'] = 10;

        if (isset($data['data']['expiration']) && empty($data['data']['expiration']))
            $data['data']['expiration'] = NULL;

        if (isset($data['data']['unit_price']) && empty($data['data']['unit_price']))
            $data['data']['unit_price'] = 0.00;

        if (isset($data['data']['unit_ws_price']) && empty($data['data']['unit_ws_price']))
            $data['data']['unit_ws_price'] = $data['data']['unit_price'];

        if (isset($data['data']['taxes']) && empty($data['data']['taxes']))
            $data['data']['taxes'] = NULL;
        else if (isset($data['data']['taxes']))  $data['data']['taxes'] = join(',', $data['data']['taxes']);

        return $data;
    }

    protected function setInstock($model)
    {
        if (isset($model['data']) && $model['data']) {
            $stockModel = new StockModel();
            $builder = $stockModel->builder();
            if ($model['singleton']) {
                $model['data']->inventory = $stockModel->where('product_id',  $model['data']->id)->findAll();
                $instock = $builder->selectSum('instock', 'total')
                    ->where('product_id',  $model['data']->id)
                    ->get()
                    ->getRowObject()
                    ->total;
                $model['data']->instock = $instock ?? 0.00;
            } else {
                $storeModel = new StoreModel();
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->inventory = $builder->where('product_id', $row->id)->get()->getResult();
                    $where = ['product_id' => $row->id];

                    if (isset($row->store_id))
                        $where = array_merge($where, ['store_id' => $row->store_id]);

                    if (isset($row->store_id))
                        $model['data'][$key]->store = $storeModel->builder()->where('id', $model['data'][$key]->store_id)->get()->getRow();

                    $instock = $builder->selectSum('instock', 'total')
                        ->where($where)
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
                $model['data']->tax = $taxModel->selectSum('rate', 'total')->whereIn('id', explode(',', $model['data']->taxes ?? ''))->first()->total ?? 0;
                $model['data']->tax_amounts = $taxModel->select('', 'total')->whereIn('id', explode(',', $model['data']->taxes ?? ''))->first()->total ?? 0;
            } else {
                foreach ($model['data'] as $key => $row) {
                    $model['data'][$key]->user = $userModel->where('id', $row->user_id)->first();
                    $model['data'][$key]->brand = $brandModel->where('id', $row->brand_id)->first();
                    $model['data'][$key]->category = $catModel->where('id', $row->category_id)->first();
                    $model['data'][$key]->unit = $unitModel->where('id', $row->unit_id)->first();
                    $model['data'][$key]->tax = $taxModel->selectSum('rate', 'total')->whereIn('id', explode(',',  $row->taxes ?? ''))->first()->total ?? 0;
                }
            }
        }
        return $model;
    }
}
