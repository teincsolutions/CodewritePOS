<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use CodeIgniter\HTTP\Response;

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
        $data = [
            'title' => 'Create Product'
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
}
