<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseModel;
use App\Models\PurchaseReturnItemModel;
use App\Models\PurchaseReturnModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use App\Models\SupplierLedgerModel;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;

class PurchaseReturnController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $stores = (new UserModel())->getMyStores();;

        $data = [
            'title' => 'Purchase Return List',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];
        return view('pages/purchase_returns/list_purchase_return', $data);
    }


    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit()
    {
        $invoice = $this->request->getVar('invoice');
        $model = new PurchaseReturnModel();
        $purchaseModel = new PurchaseModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;
        $stores = (new UserModel())->getMyStores();

        $data = [
            'title' => 'Create Purchase Return',
            'invoice' => substr((time() + 1000000000) + $lastId, 0, 10),
            'stores' => $stores,
        ];
        $whereInvoice = [
            'invoice' => $invoice,
            'order_status' => 'completed'
        ];
        $purchase = $purchaseModel->where($whereInvoice)->first();
        if ($invoice && $purchase) {
            $data = array_merge($data, ['purchase' => $purchase]);
        } else if ($invoice) {
            $data = array_merge($data, ['error' => "This invoice doesn't exist or not completed!",]);
        }
        return view('pages/purchase_returns/edit_purchase_return', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Purchase Return Details'
        ];
        $model = new PurchaseReturnModel();
        $data = array_merge($data, [
            'return' => $model->find($id),
        ]);

        return view('pages/purchase_returns/invoice', $data);
    }


    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        if (!auth()->user()->can('purchase-returns.create'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to create this record!"
            ]);

        $model = new PurchaseReturnModel();
        $returnItemModel = new PurchaseReturnItemModel();
        $stockModel = new StockModel();
        $ledger = new SupplierLedgerModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);

        $inputs['return_date'] = date('Y-m-d', strtotime($inputs['return_date']));
        $items = $this->request->getVar('items');
        if (!$items || sizeof($items) === 0) return $this->response->setJSON(
            [
                'status' => false,
                'data' => null,
                'message' => "No product selected!",
                'input' => $inputs,
            ]
        );
        unset($inputs['items']);

        $id = $this->request->getPost('id');

        $res = [
            'status' => false,
            'data' => null,
            'message' => "Purchase couldn't be save!",
            'input' => $inputs,
        ];
        $purchase = $model->where('id', $id)->first();
        $this->db = Database::connect();

        if ($purchase) $res = array_merge($res, ['message' => "Purchase Return updated successfully!"]);
        else $res = array_merge($res, ['message' => "Purchase Return created successfully!"]);

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);

            if ($saved && !$purchase) {
                $id = $model->getInsertID();
                $purchaseItems = [];
                $builder = $stockModel->builder();
                foreach ($items as $k => $row) {
                    $items[$k]['purchase_return_id'] = $id;
                    if (empty($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (is_null($items[$k]['store_id']) || empty($items[$k]['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];

                    array_push($purchaseItems, $items[$k]);
                    $stockWhere = [
                        'product_id' => $items[$k]['product_id'],
                        'store_id' => $items[$k]['store_id']
                    ];

                    if ($builder->where($stockWhere)->get()->getRowObject()) {
                        $builder->set('instock', '(instock - ' . $items[$k]['qty'] . ')', false)
                            ->update(null, $stockWhere);
                    } else {
                        $builder->insert([
                            'product_id' => $items[$k]['product_id'],
                            'store_id' =>  $items[$k]['store_id'],
                            'instock' => 0 - $items[$k]['qty']
                        ]);
                    }
                }
                $returnItemModel->insertBatch($purchaseItems);
                if ($inputs['supplier_id']) {
                    $data = [
                        'tdate' => $inputs['return_date'],
                        'supplier_id' => $inputs['supplier_id'],
                        'purchase_id' => $inputs['purchase_id'],
                        'purchase_return_id' => $id,
                        'store_id' => $inputs['store_id'],
                        'payment_type' => 'cash',
                        'ledger_type' => 'returns',
                        'debit' => 0,
                        'credit' => $inputs['paid'],
                        'user_id' => isset($inputs['user_id']) ? $inputs['user_id'] : null,
                    ];
                    if($inputs['paid'] > 0)  $ledger->save($data);
                    $purchaseModel = new PurchaseModel();
                    $purchaseModel->updatePaymentStatus($inputs['purchase_id']);
                    $data['debit'] =  $inputs['total_amount'];
                    $data['credit'] = 0;
                    $ledger->makePayment($data);
                }
            }
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            $res = array_merge($res, [
                'message' => $e->getMessage(),
            ]);
            return $this->response->setJSON($res);
        }
        if ($this->db->transStatus()) {
            $return = $model->find($id);
            $res = array_merge($res, [
                'status' => true,
                'data' => $return,
                'receipt' => view('pages/purchase_returns/pos_receipt', ['return' => $return])
            ]);
        } else {
            $res = array_merge($res, ['status' => false]);
        }
        return $this->response->setJSON($res);
    }

    /**
     * return json for receipt
     */
    public function print($id): Response
    {
        $model = new PurchaseReturnModel();
        $return = $model->where('id', $id)->first();
        $res = [
            'status' => false,
            'data' => null,
            'message' => "Invoice not found!",
        ];
        if ($return) {
            $res = array_merge($res, [
                'status' => true,
                'data' => $return,
                'receipt' =>  view('pages/purchase_returns/pos_receipt', ['return' => $return]),
                'message' => "Invoice found!",
            ]);
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
        $model = new PurchaseReturnModel();
        $model->select('purchase_returns.*');
        $model->join('purchases', 'purchases.id=purchase_returns.purchase_id');
        return $this->response->setJSON(toDatatableResult($model, $inputs,));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function stock_report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new PurchaseReturnModel();
        $builder = $model->builder();
        $builder->select('purchase_returns.*,purchases.supplier_id')
            ->selectSum('purchase_returns_items.qty', 'qty')
            ->join('purchase_returns_items', 'purchase_returns_items.purchase_return_id=purchase_returns.id')
            ->join('purchases', 'purchases.id=purchase_returns.purchase_id')
            ->where('product_id', $inputs['product_id'] ?? '')
            ->where('purchase_returns.order_status', 'completed')
            ->groupBy('purchase_returns.id');

        return $this->response->setJSON(toBuilderDatatableResult($builder, $inputs, function ($item) {
            $item->supplier = model('SupplierModel')->where('id', $item->supplier_id)->first();
            $item->store = model('StoreModel')->where('id', $item->store_id)->first();
            return $item;
        }));
    }

    /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('purchase-returns.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new PurchaseReturnModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Purchase Return deleted successfully!",
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
