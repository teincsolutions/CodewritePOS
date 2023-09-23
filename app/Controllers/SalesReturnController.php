<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SalesReturnModel;
use CodeIgniter\HTTP\Response;

class SalesReturnController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Sales Return List',
        ];
        return view('pages/sales_returns/list_sales_return', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Sales Return'
        ];

        if ($id) {
            $model = new SalesReturnModel();
            $data = array_merge($data, [
                'sales_return' => $model->find($id),
                'title' => 'Edit Sales Return',
            ]);
        }
        return view('pages/sales_returns/edit_sales_return', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Sales Return Details'
        ];
        $model = new SalesReturnModel();
        $data = array_merge($data, [
            'sales_return' => $model->find($id),
        ]);

        return view('pages/sales_returns/show_sales_return', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new SalesReturnModel();
        return $this->response->setJSON(toDatatableResult($model,$inputs));
    }
}
