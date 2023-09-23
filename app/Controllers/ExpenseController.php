<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ExpenseModel;
use CodeIgniter\HTTP\Response;

class ExpenseController extends BaseController
{
   /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Expense List',
        ];
        return view('pages/expenses/list_expense', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Expense'
        ];

        if ($id) {
            $model = new ExpenseModel();
            $data = array_merge($data, [
                'expense' => (object)$model->find($id),
                'title' => 'Edit Expense',
            ]);
        }
        return view('pages/expenses/edit_expense', $data);
    }
    public function save()
    {
        $model = new ExpenseModel();
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
        $Expense = $model->where('id',$id)->first();

        if ($Expense) {
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
        return $this->response->setJSON(toDatatableResult($model,$inputs));
    }
}
