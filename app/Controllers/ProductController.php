<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\TaxModel;
use App\Models\UnitModel;
use App\Models\UserModel;
use CodeIgniter\Database\RawSql;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class ProductController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Product List',
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

        $data = [
            'title' => 'Create Product',
            'categories' => $catModel->findAll(),
            'units' => $unitModel->findAll(),
            'taxes' => $taxModel->findAll(),
            'brands' => $brandModel->findAll(),
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
        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = auth()->user()->id;

        $id = $this->request->getPost('id');
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $product = $model->where('id', $id)->first();

        if ($product) {
            if (!auth()->user()->can('products.edit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to edit this record!"
                ]);

            if ($model->save($inputs)) {
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
            if (!auth()->user()->can('products.create'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to create this record!"
                ]);

            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Product created successfully!",
                    'data' => $model->find($model->getInsertID()),
                ]);
            } else {
                $res = array_merge($res, [
                    'status' => false,
                    'message' => "Couldn't be created!"
                ]);
            }
        }
        return $this->response->setJSON($res);
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
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ProductModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
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
     * return json for search
     * @return Response - http response
     */
    public function search(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ProductModel();
        $model->select('products.*');
        $model->join('categories', 'categories.id=products.category_id');
        $model->join('brands', 'brands.id=products.brand_id', 'left');

        return $this->response->setJSON(toDatatableResult($model, $inputs));
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
        $model->join('purchase_returns', 'purchase_returns.purchase_id=purchase_items.purchase_id', 'left');
        $model->join('purchase_returns_items', 'purchase_returns_items.purchase_return_id=purchase_returns.id AND purchase_returns_items.product_id=products.id', 'left');
        $model->select('products.id,products.name,products.barcode,products.sku,products.category_id,products.brand_id,products.unit_id,purchase_items.unit_price,products.discontinued,products.unit_qty,products.user_id,products.expiration,products.image_uri,products.min_qty,purchase_items.tax_id,purchase_items.unit_cost, (SUM(purchase_items.qty)-SUM(ifnull(purchase_returns_items.qty,0))) as qty, purchase_items.tax, purchase_items.discount,  (SUM(purchase_items.qty)-SUM(ifnull(purchase_returns_items.qty,0))) as max_qty,purchase_items.subtotal, purchase_items.store_id, purchase_items.id as purchase_item_id');
        $model->where('purchase_items.purchase_id', $this->request->getGet('purchase_id'));
        $model->groupBy('purchase_items.product_id');

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
        $model->join('sales_returns', 'sales_returns.sale_id=sales_items.sale_id', 'left');
        $model->join('sales_returns_items', 'sales_returns_items.sales_return_id=sales_returns.id AND sales_returns_items.product_id=products.id', 'left');
        $model->select('products.id,products.name,products.barcode,products.sku,products.category_id,products.brand_id,products.unit_id,sales_items.unit_cost,products.discontinued,products.unit_qty,products.user_id,products.expiration,products.image_uri,products.min_qty,sales_items.tax_id,sales_items.unit_price, (SUM(sales_items.qty)-SUM(ifnull(sales_returns_items.qty,0))) as qty, sales_items.tax, sales_items.discount,  (SUM(sales_items.qty)-SUM(ifnull(sales_returns_items.qty,0))) as max_qty,sales_items.subtotal, sales_items.store_id, sales_items.id as sale_item_id');
        $model->where('sales_items.sale_id', $this->request->getGet('sale_id'));
        $model->groupBy('sales_items.product_id');

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
}
