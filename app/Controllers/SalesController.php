<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\SalesModel;
use App\Models\StoreModel;
use CodeIgniter\HTTP\Response;

class SalesController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Sales List',
        ];
        return view('pages/sales/list_sales', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function pos($id = null)
    {
        $model = new SalesModel();
        $lastItem = $model->orderBy('id', 'asc')->first();
        $lastId = $lastItem?$lastItem->id:1;
        $storeModel = new StoreModel();
        $cusModel = new CustomerModel();
        $data = [
            'title' => 'Point of Sales',
            'invoice' => date('ymd').str_pad($lastId%10000,4,"0",STR_PAD_LEFT),
            'stores' => $storeModel->findAll(),
            'customers' => $cusModel->findAll(),
        ];

        if ($id) {
            $data = array_merge($data, [
                'sale' => (object)$model->where('id',$id)->first(),
                'title' => 'POS',
            ]);
        }
        return view('pages/sales/pos', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Sales Details'
        ];
        $model = new SalesModel();
        $data = array_merge($data, [
            'sale' => (object)$model->find($id),
        ]);

        return view('pages/sales/show_sale', $data);
    }

     /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new SalesModel();
        return $this->response->setJSON(toDatatableResult($model,$inputs));
    }
}
