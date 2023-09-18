<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreLedgerModel;
use App\Models\StoreModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class StoreLedgerController extends BaseController
{
    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit()
    {
        $storeModel = new StoreModel();
        $storeId = $this->request->getVar('store_id');
        $store = $storeModel->where('id', $storeId)->first();

        $data = [
            'stores' => $storeModel->where('status', 'opened')->findAll(),
            'title' => 'Cashup'
        ];
        if ($store) $data = array_merge($data, [
            'store' => $store,
        ]);
        else  $data = array_merge($data, [
            'store' =>  $storeModel->where('status', 'opened')->first()
        ]);
        return view('pages/cashups/cashup', $data);
    }
    public function save()
    {
        $model = new StoreLedgerModel();
        $inputs = $this->request->getVar();

        if (auth()->user())
            $inputs['user_id'] = auth()->user()->id;

        $id = $this->request->getPost('id');
        $inputs['tdate'] = date('Y-m-d', strtotime($inputs['tdate']));
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];

        $StoreLedger = $model->where('id', $id)->first();
        if ($StoreLedger) {
            if (!auth()->user()->can('purchases.edit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to edit this record!"
                ]);

            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Cash updated successfully!",
                    'data' => $model->find($id),
                ]);
            } else {
                $res = array_merge($res, [
                    'status' => false,
                    'message' => "Couldn't be updated!"
                ]);
            }
        } else {

            if (!auth()->user()->can('cashup.create'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to create this record!"
                ]);

            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Cash added successfully!",
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
        $model = new StoreLedgerModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return jwon for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('cashup.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new StoreLedgerModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Record deleted successfully!",
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
