<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ExpenseCategoryModel;
use CodeIgniter\HTTP\Response;

class ExpenseCategoryController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Expense Category List',
        ];
        return view('pages/expenses/list_category', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Expense Category'
        ];

        if ($id) {
            $model = new ExpenseCategoryModel();
            $data = array_merge($data, [
                'expense_category' => (object)$model->find($id),
                'title' => 'Edit Expense Category',
            ]);
        }
        return view('pages/expenses/edit_category', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ExpenseCategoryModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
}
