<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductTransferModel;
use CodeIgniter\HTTP\Response;

class ProductTransferController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Product Transfer List',
        ];
        return view('pages/transfers/list_product_transfer', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Product Transfer'
        ];

        if ($id) {
            $model = new ProductTransferModel();
            $data = array_merge($data, [
                'product' => $model->find($id),
                'title' => 'Edit Product Transfer',
            ]);
        }
        return view('pages/transfers/edit_product_transfer', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Product Transfer Details'
        ];
        $model = new ProductTransferModel();
        $data = array_merge($data, [
            'product' => $model->find($id),
        ]);

        return view('pages/transfers/show_product_transfer', $data);
    }

     /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ProductTransferModel();
        return $this->response->setJSON(toDatatableResult($model,$inputs));
    }
}
