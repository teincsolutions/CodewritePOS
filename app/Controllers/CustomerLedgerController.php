<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerLedgerModel;
use App\Models\CustomerModel;
use App\Models\SalesModel;
use App\Models\StoreModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Response;

class CustomerLedgerController extends BaseController
{

    /**
     * return view for list
     * @return Response - http response
     */
    public function customer_debts()
    {
        $data = [
            'title' => 'Customer Debts',
        ];
        return view('pages/account_debts/customers', $data);
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function customer_reports()
    {
        $stores = (new UserModel())->getMyStores();
        $data = [
            'title' => 'Customer Payment Reports',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];
        return view('pages/reports/customer_payments', $data);
    }
    /**
     * return view for list
     * @return Response - http response
     */
    public function customer_debt_reports()
    {
        $stores = (new UserModel())->getMyStores();
        $data = [
            'title' => 'Customer Debt Payment Reports',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];
        return view('pages/reports/customer_debt_payments', $data);
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

    /**
     * return view for edit
     * @return Response - http response
     */
    public function view($id)
    {
        $model = new CustomerLedgerModel();
        $data = [
            'ledger' => $model->find($id),
            'title' => 'Payment Recepit'
        ];
        return view('pages/ledgers/view_ledger', $data);
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

        $customerLedger = $model->where('id', $id)->first();
        if ($customerLedger) {
            if (!auth()->user()->can('customer-ledgers.edit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to edit this record!"
                ]);

            if ($customerLedger->debit > 0 && !auth()->user()->can('customer-ledgers.edit-debit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "You cannot edit a sales debit!"
                ]);

            if ($model->save($inputs)) {
                $sales = $salesModel->where('id', $customerLedger->sale_id)->first();
                $salesModel->save([
                    'id' => $customerLedger->sale_id,
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
            if (!auth()->user()->can('customer-ledgers.create'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to create this record!"
                ]);

            $sales = $salesModel->where('id', $inputs['sale_id'])->first();
            $inputs['customer_id'] = $sales->customer_id;
            $inputs['store_id'] = $sales->store_id;
            if ($model->save($inputs)) {
                $salesModel->updatePaymentStatus($inputs['sale_id']);
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

    public function bulk_payment()
    {
        $model = new CustomerLedgerModel();
        $inputs = $this->request->getVar();

        if (auth()->user())
            $inputs['user_id'] = auth()->user()->id;


        if (!auth()->user()->can('customer-ledgers.create'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to create this record!"
            ]);
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $result = $model->makePayment($inputs);
        if ($result) {
            $res = array_merge($res, [
                'status' => true,
                'message' => "Payment(s) added successfully!$result->message",
            ]);
        } else {
            $res = array_merge($res, [
                'status' => false,
                'message' => "Payment couldn't be created!"
            ]);
        }
        return $this->response->setJSON($res);
    }
    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $stores = (new UserModel())->getMyStores();
        $data = [
            'title' => 'Customer Ledger Details',
            'stores' => $stores,
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
        $model->orderBy('id', 'desc');
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new CustomerLedgerModel();
        $builder = $model->builder();
        $db = db_connect();
        $builder->select('id,created_at,tdate,sale_id,sales_return_id,payment_type,customer_id,ledger_type,user_id', false)
            ->selectSum('credit', 'total_credit')
            ->selectSum('debit', 'total_debit')
            ->groupBy('created_at')
            ->groupBy(['ledger_type', 'sales_return_id'])
            ->orderBy('tdate', 'desc')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
        return $this->response->setJSON(toBuilderDatatableResult($builder, $inputs, function ($item) use ($db) {
            $item->customer = model('CustomerModel')->where('id', $item->customer_id)->first();
            $item->sale = model('SalesModel')->where('id', $item->sale_id)->first();
            $item->user = model('UserModel')->where('id', $item->user_id)->first();
            $item->sales_return = model('SalesReturnModel')->where('id', $item->sales_return_id)->first();
            $totals = $db->table('customer_ledgers')
                ->select('SUM((credit-debit)) as total_due')
                ->where('customer_id', $item->customer_id)
                ->where('id <', $item->id)
                ->orderBy('tdate', 'desc')
                ->get()->getFirstRow();

            $item->total_due = $totals->total_due ?? 0.00;
            $item->total_balance = ($totals->total_due ?? 0) +  $item->total_credit - $item->total_debit;

            return $item;
        }));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function debt_report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new CustomerLedgerModel();
        $builder = $model->builder();
        $db = db_connect();
        $builder->select('customer_ledgers.id,customer_ledgers.created_at,tdate,sale_id,sales_return_id,customer_ledgers.payment_type,customer_ledgers.customer_id,ledger_type,customer_ledgers.user_id', false)
            ->selectSum('credit', 'total_credit')
            ->selectSum('debit', 'total_debit')
            ->join('sales', 'sales.id=customer_ledgers.sale_id')
            ->where('sales.store_closing_id !=', null)
            ->where('ledger_type', 'sales')
            ->groupBy('created_at')
            ->groupBy(['ledger_type', 'sales_return_id'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
        return $this->response->setJSON(toBuilderDatatableResult($builder, $inputs, function ($item) use ($db) {
            $item->customer = model('CustomerModel')->where('id', $item->customer_id)->first();
            $item->sale = model('SalesModel')->where('id', $item->sale_id)->first();
            $item->user = model('UserModel')->where('id', $item->user_id)->first();
            $item->sales_return = model('SalesReturnModel')->where('id', $item->sales_return_id)->first();
            $totals = $db->table('customer_ledgers')
                ->select('SUM((credit-debit)) as total_due')
                ->where('customer_id', $item->customer_id)
                ->where('id <', $item->id)
                ->get()->getFirstRow();

            $item->total_due = $totals->total_due ?? 0.00;
            $item->total_balance = ($totals->total_due ?? 0) +  $item->total_credit - $item->total_debit;

            return $item;
        }));
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

        if (!auth()->user()->can('customer-ledgers.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);
        $model = new CustomerLedgerModel();
        $ledger = $model->find($id);
        if ($ledger->debit > 0)
            return $this->response->setJSON([
                'status' => false,
                'message' => "You cannot delete a sales debit!"
            ]);

        if ($model->delete($id)) {
            $saleModel = new SalesModel();
            $saleModel->updatePaymentStatus($ledger->sale_id);
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
