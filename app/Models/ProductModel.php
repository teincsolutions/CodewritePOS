<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

use function PHPUnit\Framework\isNull;

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
        'user_id',
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
    protected $beforeInsert   = ['setDefaultBrandId'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['setDefaultBrandId'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ['setInstock'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function toDatatableResult(array $inputs = null): array
    {
        $total = $this->countAllResults();
        if (isset($inputs['date_from']) || isset($inputs['date_to'])) {
            if (!empty($inputs['date_from']) || !empty($inputs['date_to'])) {
                $this->groupStart();
                $this->where(new RawSql("DATE(" . $inputs['date_range_column'] . ")" . ' >='), $inputs['date_from']);
                $this->where(new RawSql("DATE(" . $inputs['date_range_column'] . ")" . ' <='), $inputs['date_to']);
                $this->groupEnd();
            }
        }

        if ($inputs['columns']) {
            $this->groupStart();
            foreach ($inputs['columns'] as $col) {
                if (isset($col['searchable']) && $col['searchable'])
                    $this->orLike($col['name'], $inputs['search']['value'], 'both');
                else   $this->orLike($col['name'], $inputs['search']['value'], 'both');;
            }
            $this->groupEnd();
        }
        if (isset($inputs['order'])) {
            foreach ($inputs['order'] as $order) {
                $this->orderBy($inputs['columns'][$order['column']]['name'], $order['dir']);
            }
        }
        $data = $this->findAll();
        $filtered = sizeof($data);

        return  [
            'draw' => isset($inputs['draw']) ? $inputs['draw'] : 1,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ];
    }

    protected function setDefaultBrandId(array $data)
    {
        if (isset($data['data']['brand_id']) && isNull($data['data']['brand_id']))
            $data['data']['brand_id'] = NULL;
        return $data;
    }

    protected function setInstock($model)
    {
        $stockModel = new StockModel();
        if ($model['singleton']) {
            $model['data']->inventory = $stockModel->where('product_id', $model['data']->id)->findAll();
        } else {
            foreach ($model['data'] as $key => $row) {
                $model['data'][$key]->inventory = $stockModel->where('product_id', $row->id)->findAll();
            }
        }
        return $model;
    }
}
