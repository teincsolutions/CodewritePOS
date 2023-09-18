<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,
            'firstname',
            'lastname',
            'photo_uri',
            'phone',
            'address',
            'employee_id',
            'user_id'
        ];
    }

    public function getStores($id)
    {
        $userStoreModel = new UserStoreModel();
        return $userStoreModel->where('user_id', $id)->findAll();
    }

    public function getStoreIds($id = null)
    {
        if (!$id) return [];
        $stores =  $this->getStores($id);
        return array_map(function ($item) {
            return $item->store_id;
        }, $stores);
    }

    public function getMyStores() {
        $user = auth()->user();
        $storeIds = $this->getStoreIds($user->id);
        $storeModel = new StoreModel();
        $storeModel->where('status', 'opened');
        $skipGroups = array_merge(setting('AuthGroups.disabledGroup'), [setting('AuthGroups.defaultGroup')]);

        if (!$user->inGroup(...$skipGroups))
            $storeModel->whereIn('id', array_merge($storeIds, ['']));
        $stores =  $storeModel->findAll();

        return $stores;
    }
}
