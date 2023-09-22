<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel;
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
        return $this->response->setJSON(toDatatableResult($model,$inputs));
    }
}
