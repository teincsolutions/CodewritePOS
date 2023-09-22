<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use CodeIgniter\HTTP\Response;

class CustomerController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Customer List',
        ];
        return view('pages/customers/list_customer', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Customer'
        ];

        if ($id) {
            $model = new CustomerModel();
            $data = array_merge($data, [
                'customer' => $model->find($id),
                'title' => 'Edit Customer',
            ]);
        }
        return view('pages/customers/edit_customer', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Customer Details'
        ];
        $model = new CustomerModel();
        $data = array_merge($data, [
            'customer' => $model->find($id),
        ]);

        return view('pages/customers/show_customer', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new CustomerModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
}
