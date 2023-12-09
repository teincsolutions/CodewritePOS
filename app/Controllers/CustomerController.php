<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerLedgerModel;
use App\Models\CustomerModel;
use App\Models\SalesModel;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;

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
        $stores = (new UserModel())->getMyStores();

        $data = [
            'title' => 'Customer Details',
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
            'stores' => $stores,
        ];
        $model = new CustomerModel();
        $customer =  $model->where('id', $id)->first();

        $data = array_merge($data, [
            'customer' => $customer,
            'title' => $customer->name,
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

    /**
     * return json for select2
     * @return Response- http response
     */
    public function select2(): Response
    {
        $inputs = $this->request->getVar();
        $model = new CustomerModel();
        $model->groupBy('customers.id');
        return $this->response->setJSON(toSelect2Result($model, ['name', 'phone', 'address'], $inputs, 'concat(name," (",ifnull(address,ifnull(phone,"")), ")") as text,customers.*'));
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        $model = new CustomerModel();
        $inputs = $this->request->getVar();
        $id = $this->request->getPost('id');
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $customer = $model->where('id', $id)->first();

        if ($customer) {
            if (!auth()->user()->can('customers.edit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to edit this record!"
                ]);

            // customer limit permissions
            if (setting('App.AllowCustomerLimit') === 'yes') {
                if (
                    floatval($this->request->getVar('credit_limit') ?? 0) !== floatval($customer->credit_limit ?? 0)
                    || $this->request->getVar('credit_limit_days') !== $customer->credit_limit_days
                )
                    if (!auth()->user()->can('customers.edit-limit'))
                        return $this->response->setJSON([
                            'status' => false,
                            'message' => "Don't have permission to edit customer credit limit this record!"
                        ]);
            }

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
            if (auth()->user())
                $inputs['user_id'] = auth()->user()->id;

            if (!auth()->user()->can('customers.create'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to create this record!"
                ]);

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

    public function save_debit()
    {
        $model = new CustomerModel();
        $inputs = $this->request->getVar();
        $inputs['tdate'] = date('Y-m-d', strtotime($inputs['tdate']));

        $res = $model->addInitialBalance($inputs['customer_id'], $inputs['store_id'], $inputs['amount'], $inputs['tdate']);
        $res = array_merge($res, ['input' => $inputs]);

        return $this->response->setJSON($res);
    }

    /**
     * return jwon for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('customers.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

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
