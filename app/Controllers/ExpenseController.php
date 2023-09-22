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
                'expense' => $model->find($id),
                'title' => 'Edit Expense',
            ]);
        }
        return view('pages/expenses/edit_expense', $data);
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
