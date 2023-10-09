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
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ClosingController extends BaseController
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

    public function index(): string
    {
        return view('pages/closing/list_closing');
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
        $closing = $closingModel->orderBy('id', 'desc')->first();

        $data = [
            'stores' => $storeModel->where('status', 'opened')->findAll(),
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
                ->selectSum('paid', 'total')->get()->getFirstRow()->total;

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
        $inputs = $this->request->getVar();

        if (auth()->user())
            $inputs['user_id'] = auth()->user()->id;

        $id = $this->request->getPost('id');
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $closing = $model->where('id', $id)->first();

        if ($closing) {
            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Closing updated successfully!",
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
}
