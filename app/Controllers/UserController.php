<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class UserController extends BaseController
{

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'User List',
        ];
        return view('pages/users/list_user', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create User'
        ];

        if ($id) {
            $model = new UserModel();
            $data = array_merge($data, [
                'user' => $model->where('id',$id)->first(),
                'title' => 'Edit User',
            ]);
        }
        return view('pages/users/edit_user', $data);
    }
     /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {

        $data = [
            'title' => 'User Details'
        ];
        $model = new UserModel();
        $user = $model->find($id);
        if ($user)
            $data = array_merge($data, [
                'user' => $user,
            ]);

        return view('pages/users/show_user', $data);
    }

    public function save()
    {
        $model = new UserModel();
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
        $User = $model->where('id', $id)->first();

        if ($User) {
            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "User updated successfully!",
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
                    'message' => "User created successfully!",
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
        $model = new UserModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
}
