<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel;
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
                'supplier' => $model->find($id),
                'title' => 'Edit Supplier',
            ]);
        }
        return view('pages/suppliers/edit_supplier', $data);
    }
    public function save()
    {
        $model = new SupplierModel();
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
        $Supplier = $model->where('id', $id)->first();

        if ($Supplier) {
            if (!auth()->user()->can('suppliers.edit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to edit this record!"
                ]);

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
            if (!auth()->user()->can('suppliers.create'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to create this record!"
                ]);

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
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Supplier Details',
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];
        $model = new SupplierModel();
        $supplier = $model->where('id', $id)->first();
        $data = array_merge($data, [
            'supplier' => $supplier,
            'title' => $supplier->name,
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


    /**
     * return json for select2
     * @return Response- http response
     */
    public function select2(): Response
    {
        $inputs = $this->request->getVar();
        $model = new SupplierModel();
        return $this->response->setJSON(toSelect2Result($model, ['name', 'phone', 'address'], $inputs, 'concat(name," (",ifnull(address,ifnull(phone,"")), ")") as text,suppliers.*'));
    }

    /**
     * return jwon for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('suppliers.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new SupplierModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Supplier deleted successfully!",
            ];
        } else {
            $res = [
                'status' => false,
                'message' => "Couldn't be deleted!"
            ];
        }
        return $this->response->setJSON($res);
    }
}
