<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\TaxModel;
use App\Models\UnitModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Response;
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

        $Product = $model->where('id', $id)->first();

        if ($Product) {
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
        return $this->response->setJSON(toDatatableResult($model, $inputs,function($item){
            $model = new UserModel();
            $item->user = $model->where('id',$item->user_id)->first();
            $model = new BrandModel();
            $item->brand = $model->where('id',$item->brand_id)->first();
            $model = new CategoryModel();
            $item->category = $model->where('id',$item->category_id)->first();
            $model = new UnitModel();
            $item->unit = $model->where('id',$item->unit_id)->first();
            $model = new TaxModel();
            $item->tax = $model->where('id',$item->tax_id)->first();
            return $item;
        }));
    }
}
