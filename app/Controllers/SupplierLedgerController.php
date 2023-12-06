<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseModel;
use App\Models\SupplierLedgerModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Response;

class supplierLedgerController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function supplier_debts()
    {
        $data = [
            'title' => 'Supplier Creditors',
        ];
        return view('pages/account_debts/suppliers', $data);
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function supplier_reports()
    {
        $stores = (new UserModel())->getMyStores();
        $data = [
            'title' => 'Supplier Payment Reports',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];
        return view('pages/reports/supplier_payments', $data);
    }

     /**
     * return view for list
     * @return Response - http response
     */
    public function supplier_debt_reports()
    {
        $stores = (new UserModel())->getMyStores();
        $data = [
            'title' => 'Supplier Debt Payment Reports',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];
        return view('pages/reports/supplier_debt_payments', $data);
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
        $purchasesModel = new PurchaseModel();
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
        $supplierLedger = $model->where('id', $id)->first();
        if ($supplierLedger) {
            if (!auth()->user()->can('supplier-ledgers.edit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to edit this record!"
                ]);

            if ($supplierLedger->credit > 0 && !auth()->user()->can('supplier-ledgers.edit-credit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "You cannot edit purchase credit!"
                ]);

            if ($model->save($inputs)) {
                $purchasesModel->updatePaymentStatus($inputs['purchase_id']);
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
            if (!auth()->user()->can('supplier-ledgers.create'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to create this record!"
                ]);
            $purchases = $purchasesModel->where('id', $inputs['purchase_id'])->first();
            $inputs['supplier_id'] = $purchases->supplier_id;
            $inputs['store_id'] = $purchases->store_id;

            if ($model->save($inputs)) {
                $purchasesModel->updatePaymentStatus($inputs['purchase_id']);
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
        $model = new SupplierLedgerModel();
        $purchaseModel = new PurchaseModel();
        $inputs = $this->request->getVar();

        if (auth()->user())
            $inputs['user_id'] = auth()->user()->id;


        if (!auth()->user()->can('supplier-ledgers.create'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to create this record!"
            ]);

        $inputs['tdate'] = date('Y-m-d', strtotime($inputs['tdate']));
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];

        $where = [
            'payment_status' => 'due',
            'supplier_id' => $inputs['supplier_id'],
            'store_id' =>  $inputs['store_id'],
        ];
        $purchases = $purchaseModel->where($where)
            ->orderBy('purchase_date', 'asc')
            ->findAll();

        $data = [];
        $amount = $inputs['debit'];
        foreach ($purchases as $row) {
            $due = $row->total_amount - $row->paid;
            if ($due >= $amount) {
                array_push($data, array_merge($inputs, [
                    'purchase_id' => $row->id,
                    'ledger_type' => 'purchases',
                    'debit' => $amount,
                    'store_id' => $row->store_id,
                ]));
                $amount = 0;
                break;
            } else {
                array_push($data, array_merge($inputs, [
                    'purchase_id' => $row->id,
                    'ledger_type' => 'purchases',
                    'debit' => $due,
                    'store_id' => $row->store_id,
                ]));
            }
            $amount -= $due;
        }

        if ($model->insertBatch($data)) {
            foreach ($purchases as $row)
                $purchaseModel->updatePaymentStatus($row->id);

            $bal = $amount > 0 ? " Change of GHS " . number_format($amount, 2) : "";
            $res = array_merge($res, [
                'status' => true,
                'message' => "Payment(s) added successfully!$bal",
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
        $model = new SupplierLedgerModel();
        $builder = $model->builder();
        $db = db_connect();
        $builder->select('id,created_at,tdate,ledger_type,purchase_id,purchase_return_id, supplier_id,payment_type,user_id', false)
            ->selectSum('credit', 'total_credit')
            ->selectSum('debit', 'total_debit')
            ->groupBy('created_at')
            ->groupBy(['ledger_type', 'purchase_return_id'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
        return $this->response->setJSON(toBuilderDatatableResult($builder, $inputs, function ($item) use ($db) {
            $item->purchase = model('PurchaseModel')->where('id', $item->purchase_id)->first();
            $item->purchase_return = model('PurchaseReturnModel')->where('id', $item->purchase_return_id)->first();
            $item->supplier = model('SupplierModel')->where('id', $item->supplier_id)->first();
            $item->user = model('UserModel')->where('id', $item->user_id)->first();
            $totals = $db->table('supplier_ledgers')
                ->select('SUM((credit-debit)) as total_due')
                ->where('supplier_id', $item->supplier_id)
                ->where('id <', $item->id)
                ->get()->getFirstRow();

            $item->total_due = $totals->total_due ?? 0.00;
            $item->total_balance = ($totals->total_due ?? 0) + ($item->total_credit - $item->total_debit);

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
        $model = new SupplierLedgerModel();
        $builder = $model->builder();
        $db = db_connect();
        $builder->select('supplier_ledgers.id,supplier_ledgers.created_at,tdate,supplier_ledgers.ledger_type,purchase_id,purchase_return_id,supplier_ledgers.supplier_id,supplier_ledgers.payment_type,supplier_ledgers.user_id', false)
            ->selectSum('credit', 'total_credit')
            ->selectSum('debit', 'total_debit')
            ->join('purchases', 'purchases.id=supplier_ledgers.purchase_id')
            ->where('purchases.store_closing_id !=', null)
            ->where('ledger_type','purchases')
            ->groupBy('created_at')
            ->groupBy(['ledger_type', 'purchase_return_id'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
        return $this->response->setJSON(toBuilderDatatableResult($builder, $inputs, function ($item) use ($db) {
            $item->purchase = model('PurchaseModel')->where('id', $item->purchase_id)->first();
            $item->purchase_return = model('PurchaseReturnModel')->where('id', $item->purchase_return_id)->first();
            $item->supplier = model('SupplierModel')->where('id', $item->supplier_id)->first();
            $item->user = model('UserModel')->where('id', $item->user_id)->first();
            $totals = $db->table('supplier_ledgers')
                ->select('SUM((credit-debit)) as total_due')
                ->where('supplier_id', $item->supplier_id)
                ->where('id <', $item->id)
                ->get()->getFirstRow();

            $item->total_due = $totals->total_due ?? 0.00;
            $item->total_balance = ($totals->total_due ?? 0) + ($item->total_credit - $item->total_debit);

            return $item;
        }));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function creditors_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new SupplierModel();
        $model->where('(SELECT SUM((supplier_ledgers.debit - supplier_ledgers.credit)) from supplier_ledgers where supplier_ledgers.supplier_id=suppliers.id) < ', 0, false);
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return jwon for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('supplier-ledgers.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new SupplierLedgerModel();
        $ledger = $model->find($id);

        if ($ledger->credit > 0)
            return $this->response->setJSON([
                'status' => false,
                'message' => "You cannot delete purchase credit!"
            ]);

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
