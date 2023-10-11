<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BrandModel;
use CodeIgniter\HTTP\Response;

class BrandController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Brand List',
        ];
        return view('pages/brands/list_brand', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Brand'
        ];

        if ($id) {
            $model = new BrandModel();
            $data = array_merge($data, [
                'brand' => $model->find($id),
                'title' => 'Edit Brand',
            ]);
        }
        return view('pages/brands/edit_brand', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new BrandModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        $model = new BrandModel();
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
        $brand = $model->find($id);

        if ($brand) {
            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Brand updated successfully!",
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
                    'message' => "Brand created successfully!",
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
        $model = new BrandModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Brand deleted successfully!",
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
