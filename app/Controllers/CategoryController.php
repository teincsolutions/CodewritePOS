<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class CategoryController extends BaseController
{
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        if(!auth()->loggedIn()){
             return $response->redirect(site_url('login'));
        }
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Product List',
        ];
        return view('pages/categories/list_category', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Product'
        ];

        if ($id) {
            $model = new CategoryModel();
            $data = array_merge($data, [
                'category' => $model->find($id),
                'title' => 'Edit Product',
            ]);
        }
        return view('pages/categories/edit_category', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new CategoryModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        $model = new CategoryModel();
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
        $category = $model->find($id);

        if ($category) {
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
     * return jwon for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        $model = new CategoryModel();
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
