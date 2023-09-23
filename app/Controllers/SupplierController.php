<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupplierModel;
use CodeIgniter\HTTP\Response;

class SupplierController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Supplier List',
        ];
        return view('pages/suppliers/list_supplier', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Supplier'
        ];

        if ($id) {
            $model = new SupplierModel();
            $data = array_merge($data, [
                'supplier' => (object)$model->find($id),
                'title' => 'Edit Supplier',
            ]);
        }
        return view('pages/suppliers/edit_supplier', $data);
    }
    public function save()
    {
        $model = new SupplierModel();
        $inputs = $this->request->getVar();
        if(auth()->user())
        $inputs['user_id'] = auth()->user()->id;
        $id = $this->request->getPost('id');
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $Supplier = $model->where('id',$id)->first();

        if ($Supplier) {
            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Supplier updated successfully!",
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
                    'message' => "Supplier created successfully!",
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
            'title' => 'Supplier Details'
        ];
        $model = new SupplierModel();
        $data = array_merge($data, [
            'supplier' => (object)$model->find($id),
        ]);

        return view('pages/suppliers/show_supplier', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new SupplierModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
}
