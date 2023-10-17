<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use CodeIgniter\HTTP\Response;

class InventoryController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function short_stocks()
    {
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Short Stock List',
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];
        return view('pages/inventory/short_stocks', $data);
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function outofstocks()
    {
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Out of Stock List',
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];
        return view('pages/inventory/outofstocks', $data);
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function instocks()
    {
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Instock List',
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];
        return view('pages/inventory/instocks', $data);
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
        $model->where('ifnull((SELECT sum(ifnull(stocks.instock,0)) from stocks where stocks.product_id=products.id AND stocks.store_id=' . $inputs['store_id'] . '),0) <=', 0, false);
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
        $model->where('stocks.instock >=', 0);

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
}
