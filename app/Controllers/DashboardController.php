<?php

namespace App\Controllers;

use App\Models\StoreModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{

    public function index(): string
    {
        $stores = (new UserModel())->getMyStores();
        $storeModel = new StoreModel();
        $activeStore = null;
        $context = 'user:' . user_id();
        $settings = service('settings');

        if ($this->request->getVar('store')) {
            $storeModel->where('id', $this->request->getVar('store'));
            $activeStore = $storeModel->where('status', 'opened')->first();
        } else if ($settings->get('App.DefaultStore', $context)) {
            $storeModel->where('id', $settings->get('App.DefaultStore', $context));
            $activeStore = $storeModel->where('status', 'opened')->first();
        } else {
            $activeStore = ($stores[0] ?? null);
        }
        $data = [
            'title' => 'Dashboard',
            'stores' => $stores,
            'activeStore' => $activeStore,
            'context' => $context,
            'settings' => $settings,
        ];
        return view('dashboard', $data);
    }
}
