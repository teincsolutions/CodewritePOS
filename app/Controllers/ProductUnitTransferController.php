<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductUnitTransferModel;
use App\Models\UnitTransferModel;
use CodeIgniter\HTTP\Response;

class ProductUnitTransferController extends BaseController
{

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Product Unit Transfer List',
        ];
        return view('pages/transfers/list_product_unit_transfer', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Product Unit Transfer'
        ];

        if ($id) {
            $model = new UnitTransferModel();
            $data = array_merge($data, [
                'product' => $model->find($id),
                'title' => 'Edit Product Unit Transfer',
            ]);
        }
        return view('pages/transfers/edit_product_unit_transfer', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Product Unit Transfer Details'
        ];
        $model = new UnitTransferModel();
        $data = array_merge($data, [
            'product' => $model->find($id),
        ]);

        return view('pages/transfers/show_product_unit_transfer', $data);
    }

     /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new UnitTransferModel();
        return $this->response->setJSON(toDatatableResult($model,$inputs));
    }

     /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('unit-transfers.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new UnitTransferModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Transfer deleted successfully!",
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
