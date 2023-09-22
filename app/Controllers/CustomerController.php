<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use CodeIgniter\Shield\Models\UserModel;
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
                'customer' => (object)$model->find($id),
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
            'customer' => (object)$model->find($id),
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
        return $this->response->setJSON(toDatatableResult($model, $inputs, function($item){
            $model = new UserModel();
            $item->user = $model->where('id',$item->user_id)->first();
            return $item;
        }));
    }

       /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        $model = new CustomerModel();
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
        $Customer = $model->find($id);

        if ($Customer) {
            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Customer updated successfully!",
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
                    'message' => "Customer created successfully!",
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
     * return jwon for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        $model = new CustomerModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Customer deleted successfully!",
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
