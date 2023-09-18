<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel;
use App\Models\UserModel;
use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\Response;

class AccountController extends BaseController
{
    /**
     * return view for edit
     * @return Response - http response
     */
    public function profile()
    {
        $data = [
            'title' => 'My Profile'
        ];
        $data = array_merge($data, [
            'user' => auth()->user(),
        ]);

        return view('pages/users/profile', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function settings()
    {
        $user = auth()->user();
        $storeIds = model('UserModel')->getStoreIds($user->id);
        $storeModel = new StoreModel();
        $storeModel->where('status', 'opened');
        $skipGroups = array_merge(setting('AuthGroups.disabledGroup'), [setting('AuthGroups.defaultGroup')]);
        
        if (!$user->inGroup(...$skipGroups))
        $storeModel->whereIn('id', array_merge($storeIds, ['']));
        $stores =  $storeModel->findAll();
        $data = [
            'title' => 'My Profile',
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
            'stores' => $stores,
        ];
        $data = array_merge($data, [
            'user' => $user,
        ]);

        return view('pages/users/settings', $data);
    }

    public function save()
    {
        $model = new UserModel();
        $inputs = $this->request->getVar();

        $user = auth()->user();
        $user->fill($inputs);

        if ($model->save($user)) {
            $rules  = setting('Validation.userUpdate');

            if (!$this->validateData($inputs, $rules)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => join(" and ", array_map(function ($error) {
                        return $error;
                    }, $this->validator->getErrors()))
                ]);
            }

            return $this->response->setJSON([
                'status' => true,
                'message' => "Profile updated successfully!",
                'data' => auth()->user(),
                'input' => $inputs,
            ]);
        }
    }

    public function update_password()
    {
        $model = new UserModel();
        $inputs = $this->request->getVar();


        $credentials = [
            'email'    => auth()->user()->email,
            'password' => $this->request->getPost('old_password')
        ];
        $validCreds = auth()->check($credentials);

        if (!$validCreds->isOK()) {
            return $this->response->setJSON([
                'status' => false,
                'message' =>  "Invalid Old Password"
            ]);
        }

        $user = auth()->user();
        $user->fill($inputs);

        if ($model->save($user)) {
            $rules  = setting('Validation.updatePassword');

            if (!$this->validateData($inputs, $rules)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => join(" and ", array_map(function ($error) {
                        return $error;
                    }, $this->validator->getErrors()))
                ]);
            }

            return $this->response->setJSON([
                'status' => true,
                'message' => "Password updated successfully!",
                'data' => auth()->user(),
                'input' => $inputs,
            ]);
        }
    }
}
