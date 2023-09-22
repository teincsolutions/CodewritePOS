<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseReturnModel;
use CodeIgniter\HTTP\Response;

class PurchaseReturnController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Purchase Return List',
        ];
        return view('pages/purchase_returns/list_purchase_return', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Purchase Return'
        ];

        if ($id) {
            $model = new PurchaseReturnModel();
            $data = array_merge($data, [
                'purchase_return' => (object)$model->find($id),
                'title' => 'Edit Purchase Return',
            ]);
        }
        return view('pages/purchase_returns/edit_purchase_return', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Purchase Return Details'
        ];
        $model = new PurchaseReturnModel();
        $data = array_merge($data, [
            'purchase_return' => (object)$model->find($id),
        ]);

        return view('pages/purchase_returns/show_purchase_return', $data);
    }

     /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new PurchaseReturnModel();
        return $this->response->setJSON(toDatatableResult($model,$inputs));
    }
}
