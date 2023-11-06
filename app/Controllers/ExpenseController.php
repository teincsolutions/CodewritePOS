<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ExpenseCategoryModel;
use App\Models\ExpenseModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Response;

class ExpenseController extends BaseController
{

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $stores =(new UserModel())->getMyStores();
        $data = [
            'title' => 'Expense List',
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
            'stores' => $stores,
        ];
        return view('pages/expenses/list_expense', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $eCatModel = new ExpenseCategoryModel();
        $stores =(new UserModel())->getMyStores();
        $data = [
            'title' => 'Create Expense',
            'categories' => $eCatModel->findAll(),
            'stores' => $stores,
        ];

        if ($id) {
            $model = new ExpenseModel();
            $data = array_merge($data, [
                'expense' => $model->find($id),
                'title' => 'Edit Expense',
            ]);
        }
        return view('pages/expenses/edit_expense', $data);
    }
    public function save()
    {
        $model = new ExpenseModel();
        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = auth()->user()->id;


        $inputs['expense_date'] = date('Y-m-d', strtotime($inputs['expense_date']));

        $id = $this->request->getPost('id');
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $expense = $model->where('id', $id)->first();

        if ($expense) {
            if (!auth()->user()->can('expenses.edit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to edit this record!"
                ]);

            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Expense updated successfully!",
                    'data' => $model->find($id),
                ]);
            } else {
                $res = array_merge($res, [
                    'status' => false,
                    'message' => "Couldn't be updated!"
                ]);
            }
        } else {
            if (!auth()->user()->can('expenses.create'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to create this record!"
                ]);

            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Expense created successfully!",
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
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ExpenseModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('expenses.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new ExpenseModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Expense deleted successfully!",
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
