<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\ModelException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Authentication\Passwords;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Exceptions\ValidationException;
use CodeIgniter\Shield\Models\DatabaseException;
use CodeIgniter\Shield\Traits\Viewable;
use Config\Services;
use Psr\Log\LoggerInterface;

class UserController extends BaseController
{
    use Viewable;
    /**
     * Auth Table names
     */
    private array $tables;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController(
            $request,
            $response,
            $logger
        );

        /** @var Auth $authConfig */
        $authConfig   = config('Auth');
        $this->tables = $authConfig->tables;
    }
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
                'user' => $model->where('id', $id)->first(),
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
        $user = $model->where('id', $id)->first();

        if ($user) {
            if ($model->save($inputs)) {
                if (isset($inputs['groups']))
                    $user->syncGroups(...$inputs['groups']);
                return $this->response->setJSON([
                    'status' => true,
                    'message' => "User updated successfully!",
                    'data' => $model->find($id),
                    'input' => $inputs,
                ]);
            }
        } else {
            $rules  = setting('Validation.registration');
            if (!$this->validateData($inputs, $rules)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => join(" and ", array_map(function ($error) {
                        return $error;
                    }, $this->validator->getErrors()))
                ]);
            }
            $allowedPostFields = array_keys($rules);

            $user = new User();
            $user->fill($this->request->getPost($allowedPostFields));

            $model->save($user);
            $user = $model->find($model->getInsertID());
            $user->syncGroups(...$inputs['groups']);

            return $this->response->setJSON([
                'status' => true,
                'message' => "User created successfully!",
                'input' => $inputs,
                'data' => $model->find($model->getInsertID()),
            ]);
        }
    }
      /**
     * return json for savePermissions
     * @return Response - http response
     */
    public function save_permissions($user_id=null)
    {
        $inputs = $this->request->getVar();
        $model = new UserModel();
        $user = $model->where('id', $user_id)->first();

        if (!$user) return $this->response->setJSON(
            [
                'status' => false,
                'message' => "No User Selected!",
                'input' => $inputs,
            ]
        );

        if (isset($inputs['permissions'])) {
            $user->syncPermissions(...$inputs['permissions']);
            return $this->response->setJSON(
                [
                    'status' => true,
                    'data' => $user->getPermissions(),
                    'message' => "Permission Saved Successfully!",
                    'input' => $inputs,
                ]
            );
        } else {
            return $this->response->setJSON(
                [
                    'status' => false,
                    'message' => "No Permissions Selected!",
                    'input' => $inputs,
                ]
            );
        }
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

    /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        $model = new UserModel();
        if ($model->delete($id, true)) {
            $res = [
                'status' => true,
                'message' => "User deleted successfully!",
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
