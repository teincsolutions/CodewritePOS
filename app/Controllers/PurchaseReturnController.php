<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseItemModel;
use App\Models\PurchaseModel;
use App\Models\PurchaseReturnItemModel;
use App\Models\PurchaseReturnModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use App\Models\SupplierLedgerModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Psr\Log\LoggerInterface;

class PurchaseReturnController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Purchase Return List',
            'stores' => $storeModel->where('status','opened')->findAll(),
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
        $storeModel = new StoreModel();

        $data = [
            'title' => 'Create Purchase Return',
            'invoice' => substr((time() + 1000000000) + $lastId, 0, 10),
            'stores' => $storeModel->where('status','opened')->findAll(),
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
                if ($inputs['supplier_id']){
                    $ledger->save([
                        'tdate' => $inputs['return_date'],
                        'supplier_id' => $inputs['supplier_id'],
                        'purchase_id' => $inputs['purchase_id'],
                        'purchase_return_id' => $id,
                        'payment_type' => 'cash',
                        'ledger_type' => 'returns',
                        'debit' => $inputs['total_amount'],
                        'credit' => $inputs['paid'],
                        'user_id' => isset($inputs['user_id']) ? $inputs['user_id'] : null,
                    ]);
                    $purchaseModel = new PurchaseModel();
                    $purchaseModel->updatePaymentStatus($inputs['purchase_id']);
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
                'receipt' => view('pages/purchase_returns/pos_receipt', ['returns' => $return])
            ]);
        } else {
            $res = array_merge($res, ['status' => false]);
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
        return $this->response->setJSON(toDatatableResult($model, $inputs, ));
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
