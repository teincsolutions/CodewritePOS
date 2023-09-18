<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ExpenseCategoryModel;
use App\Models\ExpenseSubCategory;
use App\Models\ExpenseSubCategoryModel;
use CodeIgniter\HTTP\Response;


class ExpenseSubCategoryController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Expense SubCategory List',
        ];
        return view('pages/expenses/list_subcategory', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $eCatModel = new ExpenseCategoryModel();
        $data = [
            'title' => 'Create Expense SubCategory',
            'categories' => $eCatModel->findAll(),
        ];

        if ($id) {
            $model = new ExpenseSubCategoryModel();
            $data = array_merge($data, [
                'expense_subcategory' => $model->find($id),
                'title' => 'Edit Expense SubCategory',
            ]);
        }
        return view('pages/expenses/edit_subcategory', $data);
    }
    public function save()
    {
        $model = new ExpenseSubCategoryModel();
        $inputs = $this->request->getVar();

        $id = $this->request->getPost('id');
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $subcategory = $model->where('id', $id)->first();

        if ($subcategory) {
            if (!auth()->user()->can('expense-categories.edit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to edit this record!"
                ]);

            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "SubCategory updated successfully!",
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

            if (!auth()->user()->can('expense-categories.create'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to create this record!"
                ]);

            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "SubCategory created successfully!",
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
        $model = new ExpenseSubCategoryModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('expense-categories.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new ExpenseSubCategoryModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "SubCategory deleted successfully!",
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
