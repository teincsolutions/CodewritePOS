<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SalesModel;
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
        $data = [
            'title' => 'Point of Sales'
        ];

        if ($id) {
            $model = new SalesModel();
            $data = array_merge($data, [
                'sale' => (object)$model->find($id),
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
