<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StockAdjustmentModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AdjustmentController extends BaseController
{
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        if(!auth()->loggedIn()){
             return $response->redirect(site_url('login'));
        }
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Adjustment List',
        ];
        return view('pages/adjustments/list_adjutment', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Adjustment'
        ];

        if ($id) {
            $model = new StockAdjustmentModel();
            $data = array_merge($data, [
                'product' => $model->find($id),
                'title' => 'Edit Product',
            ]);
        }
        return view('pages/adjustment/edit_adjustment', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Adjustment Details'
        ];
        $model = new StockAdjustmentModel();
        $data = array_merge($data, [
            'product' => $model->find($id),
        ]);

        return view('pages/products/show_product', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new StockAdjustmentModel();
        return $this->response->setJSON(toDatatableResult($model,$inputs));
    }

     /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        $model = new StockAdjustmentModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Adjustment deleted successfully!",
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
