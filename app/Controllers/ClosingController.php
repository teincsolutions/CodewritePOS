<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerLedgerModel;
use App\Models\ExpenseModel;
use App\Models\ProductTransferModel;
use App\Models\PurchaseModel;
use App\Models\PurchaseReturnModel;
use App\Models\SalesModel;
use App\Models\SalesReturnModel;
use App\Models\StoreClosingModel;
use App\Models\StoreLedgerModel;
use App\Models\StoreModel;
use App\Models\SupplierLedgerModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Response;
use Config\Database;

class ClosingController extends BaseController
{

    public function index(): string
    {
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Closing List',
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];
        return view('pages/closing/list_closing', $data);
    }

    public function show($id = null): string
    {
        $closingModel = new StoreClosingModel();

        $closing = $closingModel->where('id', $id)->first();
        $data = [
            'closing' => $closing,
            'title' => 'Details of Closing',
        ];
        if (!$closing) throw PageNotFoundException::forPageNotFound('Closing Record Not Found!');

        return view('pages/closing/show_closing', $data);
    }

    public function store(): string
    {
        $storeModel = new StoreModel();
        $saleModel = new SalesModel();
        $saleReturnModel = new SalesReturnModel();
        $purchaseReturnModel = new PurchaseReturnModel();
        $transferModel = new ProductTransferModel();
        $customerLedgerModel = new CustomerLedgerModel();
        $supplierLedgerModel = new SupplierLedgerModel();
        $expenseModel = new ExpenseModel();
        $closingModel = new StoreClosingModel();
        $storeLedgerModel = new StoreLedgerModel();

        $storeId = $this->request->getVar('store_id');
        $store = $storeModel->where('id', $storeId)->first();
        $closingWhere = ['status' => 'pending'];
        $closing = $closingModel->where($closingWhere)->orderBy('id', 'desc')->first();

        $data = [
            'stores' => $storeModel->where('status', 'opened')->findAll(),
            'title' => 'Store Closing',
        ];

        if ($store) {
            $opening_balance = $closing ? $closing->closing_balance : 0.0;
            $cashup =  $storeLedgerModel->builder()
                ->selectSum('credit', 'total')
                ->where('store_closing_id', null)
                ->where('store_id', $storeId)->get()
                ->getRowObject()->total;

            $where = [
                'sales.store_closing_id' => null,
                'sales.store_id' => $store->id,
            ];
            $customer_payment =  $customerLedgerModel->builder()->where($where)
                ->join('sales', 'sales.id=customer_ledgers.sale_id')
                ->selectSum('credit', 'total')->get()->getFirstRow()->total;

            $sale_total = $saleModel->builder()->where($where)
                ->where('type', 'walk-in-customer')
                ->where('order_status', 'completed')
                ->selectSum('total_amount', 'total')->get()->getFirstRow()->total;

            $sale_return_total = $saleReturnModel->builder()->where($where)
                ->join('sales', 'sales.id=sales_returns.sale_id')
                ->selectSum('sales_returns.paid', 'total')->get()->getFirstRow()->total;
            $where = [
                'purchases.store_closing_id' => null,
                'purchases.store_id' => $store->id,
            ];
            $supplier_payment =  $supplierLedgerModel->builder()->where($where)
                ->join('purchases', 'purchases.id=supplier_ledgers.purchase_id')
                ->selectSum('debit', 'total')->get()->getFirstRow()->total;

            $purchase_return_total = $purchaseReturnModel->builder()->where($where)
                ->join('purchases', 'purchases.id=purchase_returns.purchase_id')
                ->selectSum('purchase_returns.paid', 'total')->get()->getFirstRow()->total;
            $where = [
                'store_closing_id' => null,
                'store_id' => $store->id,
            ];
            $expense_total = $expenseModel->builder()->where($where)
                ->selectSum('amount', 'total')->get()->getFirstRow()->total;
            $where = [
                'store_closing_id' => null,
                'from_store_id' => $store->id,
            ];
            $product_transfer_total = $transferModel->builder()->where($where)
                ->selectSum('paid', 'total')->get()->getFirstRow()->total;

            $data = array_merge($data, [
                'store' => $store,
                'opening_balance' => $opening_balance,
                'cashup' => $cashup,
                'expense_total' => $expense_total,
                'customer_payment' => $customer_payment,
                'supplier_payment' => $supplier_payment,
                'sale_total' => $sale_total,
                'product_transfer_total' => $product_transfer_total,
                'sale_return_total' => $sale_return_total,
                'purchase_return_total' => $purchase_return_total,
            ]);
        }
        return view('pages/closing/store', $data);
    }

    public function save()
    {
        $model = new StoreClosingModel();
        $saleModel = new SalesModel();
        $saleReturnModel = new SalesReturnModel();
        $purchaseModel = new PurchaseModel();
        $purchaseReturnModel = new PurchaseReturnModel();
        $transferModel = new ProductTransferModel();
        $customerLedgerModel = new CustomerLedgerModel();
        $supplierLedgerModel = new SupplierLedgerModel();
        $expenseModel = new ExpenseModel();
        $storeLedgerModel = new StoreLedgerModel();

        $inputs = $this->request->getVar();

        if (auth()->user())
            $inputs['user_id'] = auth()->user()->id;

        if (!auth()->user()->can('closing.create'))
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
        $this->db = Database::connect();

        try {
            $this->db->transException(true)->transStart();
            if ($model->save($inputs)) {
                $id = $model->getInsertID();
                $data = ['store_closing_id' => $id];
                $where = ['store_closing_id' => null];
                $saleModel->where($where);
                $saleModel->update(null, $data);
                $purchaseModel->where($where);
                $purchaseModel->update(null, $data);
                $purchaseReturnModel->where($where);
                $purchaseReturnModel->update(null, $data);
                $saleReturnModel->where($where);
                $saleReturnModel->update(null, $data);
                $storeLedgerModel->where($where);
                $storeLedgerModel->update(null, $data);
                $customerLedgerModel->where($where);
                $customerLedgerModel->update(null, $data);
                $supplierLedgerModel->where($where);
                $supplierLedgerModel->update(null, $data);
                $expenseModel->where($where);
                $expenseModel->update(null, $data);
                $transferModel->where($where);
                $transferModel->update(null, $data);

                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Closing created successfully!",
                    'data' => $model->find($model->getInsertID()),
                ]);
            } else {
                $res = array_merge($res, [
                    'status' => false,
                    'message' => "Couldn't be created!"
                ]);
            }
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            $res = array_merge($res, [
                'message' => $e->getMessage(),
            ]);
        }
        return $this->response->setJSON($res);
    }

    /**
     * return json for update
     * @return Response - http response
     */
    public function update()
    {

        $model = new StoreClosingModel();
        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['approval_user_id'] = auth()->user()->id;
        if ($inputs['status'] === 'approved')
            $inputs['approved_at'] = date('Y-m-d H:i:s');
        else   $inputs['approved_at'] = null;

        $id = $this->request->getPost('id');
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $closing = $model->where('id', $id)->first();

        if ($closing) {
            if (!auth()->user()->can('closing.approve'))
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Don't have permission to approve this record!"
                ]);

            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Status updated successfully!",
                    'data' => $model->find($id),
                ]);
            } else {
                $res = array_merge($res, [
                    'status' => false,
                    'message' => "Couldn't be updated!"
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
        $model = new StoreClosingModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return jwon for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('closing.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new StoreClosingModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Closing deleted successfully!",
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
