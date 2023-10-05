<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerLedgerModel;
use App\Models\CustomerModel;
use App\Models\SalesModel;
use App\Models\SupplierModel;
use CodeIgniter\Database\RawSql;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class CustomerLedgerController extends BaseController
{
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        if (!auth()->loggedIn()) {
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
            'title' => 'Create Customer Ledger'
        ];

        if ($id) {
            $model = new CustomerLedgerModel();
            $data = array_merge($data, [
                'ledger' => $model->find($id),
                'title' => 'Edit Customer Ledger',
            ]);
        }
        return view('pages/ledgers/edit_ledger', $data);
    }
    public function save()
    {
        $model = new CustomerLedgerModel();
        $salesModel = new SalesModel();
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
        $CustomerLedger = $model->where('id', $id)->first();
        if ($CustomerLedger) {
            if ($model->save($inputs)) {
                $sales = $salesModel->where('id', $CustomerLedger->sale_id)->first();
                $salesModel->save([
                    'id' => $CustomerLedger->sale_id,
                    'payment_status' => (($sales->total_amount - $sales->paid > 0) ? 'due' : 'paid')
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
                $sales = $salesModel->where('id', $inputs['sale_id'])->first();
                $salesModel->save([
                    'id' => $inputs['sale_id'],
                    'payment_status' => (($sales->total_amount - $sales->paid > 0) ? 'due' : 'paid')
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
            'title' => 'Customer Ledger Details'
        ];
        $model = new CustomerLedgerModel();
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
        $model = new CustomerLedgerModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function debtors_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new CustomerModel();
        $model->where('(SELECT SUM((customer_ledgers.credit - customer_ledgers.debit)) from customer_ledgers where customer_ledgers.customer_id=customers.id) < ', 0, false);

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return jwon for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        $model = new CustomerLedgerModel();
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
