<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\StoreModel;
use App\Models\StoreProductModel;
use App\Models\TaxModel;
use App\Models\UnitModel;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;

class ProductController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $catModel = new CategoryModel();
        $unitModel = new UnitModel();
        $brandModel = new BrandModel();
        $taxModel = new TaxModel();
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Product List',
            'categories' => $catModel->findAll(),
            'units' => $unitModel->findAll(),
            'taxes' => $taxModel->findAll(),
            'brands' => $brandModel->findAll(),
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];
        return view('pages/products/list_product', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $catModel = new CategoryModel();
        $unitModel = new UnitModel();
        $brandModel = new BrandModel();
        $taxModel = new TaxModel();
        $stores =(new UserModel())->getMyStores();

        $data = [
            'title' => 'Create Product',
            'categories' => $catModel->findAll(),
            'units' => $unitModel->findAll(),
            'taxes' => $taxModel->findAll(),
            'brands' => $brandModel->findAll(),
            'stores' => $stores,
        ];

        if ($id) {
            $model = new ProductModel();
            $data = array_merge($data, [
                'product' => $model->find($id),
                'title' => 'Edit Product',
            ]);
        }
        return view('pages/products/edit_product', $data);
    }

    public function save()
    {
        $model = new ProductModel();
        $storeProductModel = new StoreProductModel();
        $inputs = $this->request->getVar();
        $id = $this->request->getPost('id');
        $items = $this->request->getVar('items') ?? [];

        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $product = $model->where('id', $id)->first();
        $this->db = Database::connect();
        $builder = $storeProductModel->builder();

        try {
            $this->db->transException(true)->transStart();

            if ($product) {
                if (!auth()->user()->can('products.edit'))
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => "Don't have permission to edit this record!"
                    ]);

                if ($model->save($inputs)) {
                    foreach ($items as $key => $item)
                        $items[$key] = array_merge($item, ['product_id' => $id]);

                    $res = array_merge($res, [
                        'status' => true,
                        'message' => "Product updated successfully!",
                        'data' => $model->find($id),
                    ]);
                } else {
                    $res = array_merge($res, [
                        'status' => false,
                        'message' => "Couldn't be updated!"
                    ]);
                }
            } else {
                if (auth()->user())
                    $inputs['user_id'] = auth()->user()->id;

                if (!auth()->user()->can('products.create'))
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => "Don't have permission to create this record!"
                    ]);

                if ($model->save($inputs)) {
                    $id = $model->getInsertID();
                    foreach ($items as $key => $item)
                        $items[$key] = array_merge($item, ['product_id' => $id]);

                    $res = array_merge($res, [
                        'status' => true,
                        'message' => "Product created successfully!",
                        'data' => $model->find($id),
                    ]);
                } else {
                    $res = array_merge($res, [
                        'status' => false,
                        'message' => "Couldn't be created!"
                    ]);
                }
            }
            if (sizeof($items) > 0)
                $builder->upsertBatch($items);

            $this->db->transComplete();
        } catch (DatabaseException $e) {
            $res = array_merge($res, [
                'message' => $e->getMessage(),
            ]);
            return $this->response->setJSON($res);
        }
        if ($this->db->transStatus()) {
            return $this->response->setJSON($res);
        }
    }
    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {

        $data = [
            'title' => 'Product Details'
        ];
        $model = new ProductModel();
        $product = $model->find($id);
        if ($product)
            $data = array_merge($data, [
                'product' => $product,
            ]);

        return view('pages/products/show_product', $data);
    }

    /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('products.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new ProductModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Product deleted successfully!",
            ];
        } else {
            $res = [
                'status' => false,
                'message' => "Couldn't be deleted!"
            ];
        }
        return $this->response->setJSON($res);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $inputs['length'] = 1000;
        $inputs['start'] = 0;
        
        $model = new ProductModel();
        if (setting('App.ProductDiffForStore') === 'yes') {
            $model->select('products.id,products.name,products.barcode,products.sku,products.pdiscount,products.description,' .
                'products.category_id,products.brand_id,products.unit_id,products.unit_qty,products.tax_id,' .
                'products.user_id,products.expiration,store_products.*');

            $model->join('store_products', 'store_products.product_id=products.id');
            $model->where('store_id', $inputs['store_id'] ?? 0);
        } else $model->select('products.*');

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
    /**
     * return json for datatables
     * @return Response - http response
     */
    public function expired_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $inputs['length'] = 1000;
        $inputs['start'] = 0;
        
        $model = new ProductModel();
        $model->where('expiration <=', date('Y-m-d', time()));
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
    /**
     * return json for search
     * @return Response - http response
     */
    public function search(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ProductModel();
        if (setting('App.ProductDiffForStore') === 'yes') {
            $model->select('products.id,products.name,products.barcode,products.sku,products.pdiscount,products.description,' .
                'products.category_id,products.brand_id,products.unit_id,products.unit_qty,products.tax_id,' .
                'products.user_id,products.expiration,store_products.*');

            $model->join('store_products', 'store_products.product_id=products.id');
            $model->where('store_id', $inputs['store_id'] ?? 0);
        } else $model->select('products.*');

        $model->join('categories', 'categories.id=products.category_id');
        $model->join('brands', 'brands.id=products.brand_id', 'left');
        $model->join('units', 'units.id=products.unit_id');

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for search
     * @return Response - http response
     */
    public function select2(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ProductModel();
        $builder = $model->builder();

        $builder->join('units', 'units.id=products.unit_id');
        $builder->join('categories', 'categories.id=products.category_id');
        $builder->join('brands', 'brands.id=products.brand_id', 'left');
        $builder->join('stocks', 'stocks.product_id=products.id', 'left');
        $builder->where('products.deleted_at', null);
        $builder->groupBy('products.id');
        if (isset($inputs['exclude']))
            $builder->whereNotIn('products.id', $inputs['exclude']);
        return $this->response->setJSON(toSelect2BuilderResult(
            $builder,
            ['products.sku', 'products.name', 'brands.name', 'units.label', 'categories.name'],
            $inputs,
            'concat(ifnull(concat(products.sku," "),""),products.name," ",ifnull(brands.name,"")," (",units.label, ")"," ₵",products.unit_price) as text, products.*,sum(ifnull(stocks.instock,0)) as instock',
        ));
    }
    /**
     * return json for search
     * @return Response - http response
     */
    public function purchase_search(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ProductModel();
        $model->join('purchase_items', 'purchase_items.product_id=products.id');
        $model->join('purchase_returns_items', 'purchase_returns_items.purchase_item_id=purchase_items.id', 'left');
        $model->select('products.id,products.name,products.barcode,products.sku,products.category_id,products.brand_id,' .
            'products.unit_id,products.discontinued,products.unit_qty,products.user_id,products.expiration,products.image_uri,' .
            'products.min_qty,purchase_items.unit_price,purchase_items.tax_id,purchase_items.unit_cost,' .
            '(purchase_items.qty-SUM(ifnull(purchase_returns_items.qty,0))) as max_qty, purchase_items.tax,' .
            'purchase_items.discount,purchase_items.subtotal,purchase_items.store_id,purchase_items.id as purchase_item_id');
        $model->where('purchase_items.purchase_id', $this->request->getGet('purchase_id'));
        $model->groupBy('purchase_items.id');

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for search
     * @return Response - http response
     */
    public function sale_search(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ProductModel();
        $model->join('sales_items', 'sales_items.product_id=products.id');
        $model->join('sales_returns_items', 'sales_returns_items.sale_item_id=sales_items.id', 'left');
        $model->select('products.id,products.name,products.barcode,products.sku,products.category_id,' .
            'products.brand_id,products.unit_id,products.discontinued,products.unit_qty,products.user_id,' .
            'products.expiration,products.image_uri,products.min_qty,sales_items.unit_price,sales_items.unit_cost,' .
            'sales_items.tax_id,sales_items.qty, (sales_items.qty-SUM(ifnull(sales_returns_items.qty,0))) as max_qty, sales_items.tax,' .
            'sales_items.discount,sales_items.subtotal,sales_items.store_id,sales_items.id as sale_item_id');
        $model->where('sales_items.sale_id', $this->request->getGet('sale_id'));
        $model->groupBy('sales_items.id');

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
}
