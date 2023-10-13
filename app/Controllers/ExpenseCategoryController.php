<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ExpenseCategoryModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

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
                'expense_category' => $model->find($id),
                'title' => 'Edit Expense Category',
            ]);
        }
        return view('pages/expenses/edit_category', $data);
    }
    public function save()
    {
        $model = new ExpenseCategoryModel();
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
        $Category = $model->where('id', $id)->first();

        if ($Category) {
            if (!auth()->user()->can('expense-categories.edit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to edit this record!"
                ]);

            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Category updated successfully!",
                    'data' => $model->find($id),
                ]);
            } else {
                $res = array_merge($res, [
                    'status' => false,
                    'message' => "Couldn't be updated!"
                ]);
            }
        } else {
            if (!auth()->user()->can('expense-categories.create'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to create this record!"
                ]);

            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Category created successfully!",
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
        $model = new ExpenseCategoryModel();
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

        $model = new ExpenseCategoryModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Category deleted successfully!",
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
