<?php

namespace App\Models;

use CodeIgniter\Model;

use function PHPUnit\Framework\isNull;

class PurchaseItemModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'purchase_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'unit_price',
        'purchase_id',
        'store_id',
        'qty',
        'tax',
        'tax_id',
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
    protected $beforeInsert   = ['setDefaultId'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['setDefaultId'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setDefaultId(array $data)
    {
        if (isset($data['data']['tax_id']) && isNull($data['data']['tax_id']))
            $data['data']['tax_id'] = NULL;
        return $data;
    }

    public function getTotalAmount(): float
    {
        $result = $this->selectSum('subtotal', 'total')->first();
        return $result->total ? $result->total : 0.00;
    }

    public function getTodayTotalAmount(): float
    {
        $result = $this->selectSum('subtotal', 'total')
            ->join('purchases', 'purchases.id=purchase_items.purchase_id')
            ->where('purchase_date', date('Y-m-d', time()))
            ->first();
        return $result->total ? $result->total : 0.00;
    }
}
