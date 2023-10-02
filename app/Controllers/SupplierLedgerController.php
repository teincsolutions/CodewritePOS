<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseModel;
use App\Models\SupplierLedgerModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class SupplierLedgerController extends BaseController
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
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Supplier Ledger'
        ];

        if ($id) {
            $model = new SupplierLedgerModel();
            $data = array_merge($data, [
                'ledger' => $model->find($id),
                'title' => 'Edit Supplier Ledger',
            ]);
        }
        return view('pages/ledgers/edit_ledger', $data);
    }
    public function save()
    {
        $model = new SupplierLedgerModel();
        $salesModel = new PurchaseModel();
        $inputs = $this->request->getVar();
        
        if(auth()->user())
        $inputs['user_id'] = auth()->user()->id;

        $id = $this->request->getPost('id');
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $SupplierLedger = $model->where('id',$id)->first();
        if ($SupplierLedger) {
            if ($model->save($inputs)) {
                $sales = $salesModel->where('id',$inputs['sale_id'])->first();
                $salesModel->save([
                    'id'=> $inputs['sale_id'],
                    'payment_status' => (($sales->total_amount - $sales->paid > 0)?'due':'paid')
                ]);
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Payment updated successfully!",
                    'data' => $model->find($id),
                ]);
            } else {
                $res = array_merge($res, [
                    'status' => false,
                    'message' => "Couldn't be updated!"
                ]);
            }
        } else {
            if ($model->save($inputs)) {
                $sales = $salesModel->where('id',$inputs['sale_id'])->first();
                $salesModel->save([
                    'id'=> $inputs['sale_id'],
                    'payment_status' => (($sales->total_amount - $sales->paid > 0)?'due':'paid')
                ]);

                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Payment added successfully!",
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
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Supplier Ledger Details'
        ];
        $model = new SupplierLedgerModel();
        $data = array_merge($data, [
            'ledger' => $model->find($id),
        ]);

        return view('pages/ledgers/show_ledger', $data);
    }

     /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new SupplierLedgerModel();
        return $this->response->setJSON(toDatatableResult($model,$inputs));
    }
}
