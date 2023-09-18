<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\PurchaseItemModel;
use App\Models\PurchaseReturnItemModel;
use App\Models\QuoteItemModel;
use App\Models\SalesItemModel;
use App\Models\SalesReturnItemModel;
use App\Models\StockModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Response;

class InventoryController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function stock_report()
    {
        $stores = (new UserModel())->getMyStores();

        $data = [
            'title' => 'Inventory Report',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];
        return view('pages/reports/stocks', $data);
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function view_stock_report($id = null)
    {
        $stores = (new UserModel())->getMyStores();
        $productModel = new ProductModel();

        $product = $productModel->where('id', $id)->first();
        if (!$product) throw PageNotFoundException::forPageNotFound("Couldn't find Product!");

        $data = [
            'title' => $product->name . ' - Inventory Report',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
            'product' => $product,
        ];
        return view('pages/reports/view_stock_report', $data);
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function short_stocks()
    {
        $stores = (new UserModel())->getMyStores();
        $data = [
            'title' => 'Short Stock List',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];
        return view('pages/inventory/short_stocks', $data);
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function outofstocks()
    {
        $stores = (new UserModel())->getMyStores();
        $data = [
            'title' => 'Out of Stock List',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];
        return view('pages/inventory/outofstocks', $data);
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function instocks()
    {
        $stores = (new UserModel())->getMyStores();

        $data = [
            'title' => 'Instock List',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];
        return view('pages/inventory/instocks', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function stock_report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $saleItemModel = new SalesItemModel();
        $purchaseItemModel = new PurchaseItemModel();
        $saleReturnItemModel = new SalesReturnItemModel();
        $purchaseReturnItemModel = new PurchaseReturnItemModel();
        $quoteItemModel = new QuoteItemModel();
        $stockModel = new StockModel();
        $where = [];
        if (isset($inputs['store_id'])  && !empty($inputs['store_id']))
            $where = ['store_id' => $inputs['store_id']];

        $saleQuery = $saleItemModel->builder()->selectSum('qty')->where('product_id', 'products.id', false)->where($where);
        $purchaseQuery = $purchaseItemModel->builder()->selectSum('qty')->where('product_id', 'products.id', false)->where($where);
        $sReturnQuery = $saleReturnItemModel->builder()->selectSum('qty')->where('product_id', 'products.id', false)->where($where);
        $pReturnQuery = $purchaseReturnItemModel->builder()->selectSum('qty')->where('product_id', 'products.id', false)->where($where);
        $quoteQuery = $quoteItemModel->builder()->selectSum('qty')->where('product_id', 'products.id', false)->where($where);
        $inStockQuery = $stockModel->builder()->selectSum('instock')->where('product_id', 'products.id', false)->where($where);

        $model = new ProductModel();
        $builder = $model->builder();
        $builder->select('products.*,units.label as unit_label')
            ->selectSubquery($saleQuery, 'qtySold')
            ->selectSubquery($purchaseQuery, 'qtyOrdered')
            ->selectSubquery($sReturnQuery, 'qtysaleReturned')
            ->selectSubquery($pReturnQuery, 'qtyOrderReturned')
            ->selectSubquery($quoteQuery, 'qtyQuoted')
            ->selectSubquery($inStockQuery, 'qtyInstock')
            ->where('products.deleted_at', null)
            ->join("units", "units.id=products.unit_id");

        return $this->response->setJSON(toBuilderDatatableResult($builder, $inputs));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function instock_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new StockModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function outofstock_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ProductModel();
        $model->select("products.*");
        $model->where('ifnull((SELECT sum(ifnull(stocks.instock,0)) from stocks where stocks.product_id=products.id AND stocks.store_id=' . $inputs['store_id'] . '),0) <=', 0, false)
            ->where('products.deleted_at', null);
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function short_stock_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new StockModel();
        $model->select("stocks.*");
        $model->join('products', 'products.id=stocks.product_id');
        $model->where('stocks.instock <=', 'products.min_qty', false);
        $model->where('stocks.instock >=', 0)
        ->where('products.deleted_at', null);

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
}
