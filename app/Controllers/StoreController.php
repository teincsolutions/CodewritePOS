<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Response;

class StoreController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Store List',
        ];
        return view('pages/stores/list_store', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Store'
        ];

        if ($id) {
            $model = new StoreModel();
            $data = array_merge($data, [
                'store' => $model->find($id),
                'title' => 'Edit Store',
            ]);
        }
        return view('pages/stores/edit_store', $data);
    }
    public function save()
    {
        $model = new StoreModel();
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
        $Store = $model->where('id',$id)->first();

        if ($Store) {
            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Store updated successfully!",
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
                    'message' => "Store created successfully!",
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
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Store Details'
        ];
        $model = new StoreModel();
        $data = array_merge($data, [
            'store' => $model->find($id),
        ]);

        return view('pages/stores/show_store', $data);
    }

     /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new StoreModel();
        return $this->response->setJSON(toDatatableResult($model,$inputs,function($item){
            $model = new UserModel();
            $item->user = $model->where('id',$item->user_id)->first();
            return $item;
        }));
    }
}
