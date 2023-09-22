<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseModel;
use CodeIgniter\HTTP\Response;

class PurchaseController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Purchase List',
        ];
        return view('pages/purchases/list_purchase', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Purchase'
        ];

        if ($id) {
            $model = new PurchaseModel();
            $data = array_merge($data, [
                'purchase' => (object)$model->find($id),
                'title' => 'Edit Purchase',
            ]);
        }
        return view('pages/purchases/edit_purchase', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Purchase Details'
        ];
        $model = new PurchaseModel();
        $data = array_merge($data, [
            'purchase' => (object)$model->find($id),
        ]);

        return view('pages/purchases/show_purchase', $data);
    }

     /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new PurchaseModel();
        return $this->response->setJSON(toDatatableResult($model,$inputs));
    }
}
