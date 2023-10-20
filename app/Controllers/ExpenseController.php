<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ExpenseCategoryModel;
use App\Models\ExpenseModel;
use App\Models\StoreModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ExpenseController extends BaseController
{

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Expense List',
            'stores' => $storeModel->where('status', 'opened')->findAll(),
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
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Create Expense',
            'categories' => $eCatModel->where('status', 'opened')->findAll(),
            'stores' => $storeModel->where('status', 'opened')->findAll(),
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
        $Expense = $model->where('id', $id)->first();

        if ($Expense) {
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
