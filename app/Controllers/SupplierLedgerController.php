<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseModel;
use App\Models\SupplierLedgerModel;
use App\Models\SupplierModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class SupplierLedgerController extends BaseController
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
        $SupplierLedger = $model->where('id', $id)->first();
        if ($SupplierLedger) {
            if (!auth()->user()->can('purchases.edit'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to edit this record!"
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
        return $this->response->setJSON(toDatatableResult($model, $inputs));
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
