<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseItemModel;
use App\Models\PurchaseModel;
use App\Models\PurchaseReturnModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use App\Models\SupplierLedgerModel;
use App\Models\SupplierModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Psr\Log\LoggerInterface;


class PurchaseController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $supModel = new SupplierModel();
        $storeModel = new StoreModel();

        $data = [
            'title' => 'Purchase List',
            'suppliers' => $supModel->findAll(),
            'stores' => $storeModel->where('status','opened')->findAll(),
        ];

        return view('pages/purchases/list_purchase', $data);
    }

        /**
     * return view for list
     * @return Response - http response
     */
    public function daily_report()
    {
        $storeModel = new StoreModel();

        $data = [
            'title' => 'Daily Purchase Report',
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];

        return view('pages/reports/daily_purchases', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $model = new PurchaseModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;
        $storeModel = new StoreModel();
        $ledgerModel = new SupplierLedgerModel();
        $supModel = new SupplierModel();
        $returnModel = new PurchaseReturnModel();

        $purchaseWhere = ['purchase_date' => date('Y-m-d', time()), 'user_id' => (auth()->user()->id ?? 0)];
        $returnWhere = ['return_date' => date('Y-m-d', time()), 'user_id' => (auth()->user()->id ?? 0)];
        $holdWhere = ['order_status' => 'pending', 'user_id' => (auth()->user()->id ?? 0)];
        $ledgerWhere = ['tdate' => date('Y-m-d', time()), 'user_id' => (auth()->user()->id ?? 0)];

        $data = [
            'title' => 'Purchase Order',
            'invoice' => substr(time() + $lastId, 0, 10),
            'stores' => $storeModel->where('status','opened')->findAll(),
            'suppliers' => $supModel->findAll(),
            'purchaseList' => $model->where($purchaseWhere)->findAll(),
            'returnList' => $returnModel->where($returnWhere)->findAll(),
            'ledgerList' => $ledgerModel->where($ledgerWhere)->findAll(),
            'purchasesOnHold' =>  $model->where($holdWhere)->findAll(),
        ];

        if ($id) {
            $purchases = $model->where(['id' => $id, 'order_status' => 'pending'])->first();
            $data = array_merge($data, [
                'purchase' => $model->where('id', $id)->first(),
                'title' => 'Ponit of Purchase - Resume',
                'purchases' => $purchases,
            ]);
            if (!$purchases)  $data = array_merge($data, [
                'error' => "This purchases doesn't exist or may not be hold!",
            ]);
        }
        return view('pages/purchases/edit_purchase', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Purchase Details'
        ];
        $model = new PurchaseModel();
        $data = array_merge($data, [
            'purchase' => $model->find($id),
        ]);

        return view('pages/purchases/invoice', $data);
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        $model = new PurchaseModel();
        $purchasesItemModel = new PurchaseItemModel();
        $stockModel = new StockModel();
        $ledger = new SupplierLedgerModel();

        if (!auth()->user()->can('purchases.create'))
        return $this->response->setJSON([
            'status' => false,
            'message' => "Don't have permission to create this record!"
        ]);

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);

        unset($inputs['items']);

        $inputs['purchase_date'] = date('Y-m-d', strtotime($inputs['purchase_date']));

        $items = $this->request->getVar('items');
        if (!$items) return $this->response->setJSON(
            [
                'status' => false,
                'data' => null,
                'message' => "No product selected!",
                'input' => $inputs,
            ]
        );
        $id = $this->request->getPost('id');

        $res = [
            'status' => false,
            'data' => null,
            'message' => "Purchase couldn't be save!",
            'input' => $inputs,
        ];
        $purchases = $model->where('id', $id)->first();
        $this->db = Database::connect();

        if ($purchases) $res = array_merge($res, ['message' => "Purchase updated successfully!"]);
        else $res = array_merge($res, ['message' => "Purchase created successfully!"]);

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);

            if ($saved && !$purchases) {
                $id = $model->getInsertID();
                $purchasesItems = [];
                $builder = $stockModel->builder();
                foreach ($items as $k => $row) {
                    $items[$k]['purchase_id'] = $id;
                    if (empty($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (is_null($items[$k]['store_id']) || empty($items[$k]['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];

                    array_push($purchasesItems, $items[$k]);
                    $stockWhere = [
                        'product_id' => $items[$k]['product_id'],
                        'store_id' => $items[$k]['store_id']
                    ];

                    if ($builder->where($stockWhere)->get()->getRowObject()) {
                        $builder->set('instock', '(instock + ' . $items[$k]['qty'] . ')', false)
                            ->update(null, $stockWhere);
                    } else {
                        $builder->insert([
                            'product_id' => $items[$k]['product_id'],
                            'store_id' =>  $items[$k]['store_id'],
                            'instock' => ($items[$k]['qty'])
                        ]);
                    }
                }
                $purchasesItemModel->insertBatch($purchasesItems);
                $purchases = $model->find($id);
                if ($inputs['supplier_id'])
                    $ledger->save([
                        'tdate' => $inputs['purchase_date'],
                        'supplier_id' => $inputs['supplier_id'],
                        'purchase_id' => $purchases->id,
                        'store_id' => $inputs['store_id'],
                        'payment_type' => $inputs['payment_type'],
                        'ledger_type' => 'purchases',
                        'credit' => $inputs['total_amount'],
                        'debit' => ($inputs['payment_status'] === 'paid' ? $inputs['total_amount'] : $inputs['paid']),
                        'user_id' => isset($inputs['user_id']) ? $inputs['user_id'] : null,
                    ]);
            }
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            $res = array_merge($res, [
                'message' => $e->getMessage(),
            ]);
            return $this->response->setJSON($res);
        }
        if ($this->db->transStatus()) {
            $purchases = $model->find($id);
            $res = array_merge($res, [
                'status' => true,
                'data' => $purchases,
                'receipt' => view('pages/purchases/pos_receipt', ['purchases' => $purchases])
            ]);
        } else {
            $res = array_merge($res, ['status' => false]);
        }
        return $this->response->setJSON($res);
    }

    /**
     * return json for hold
     * @return Response - http response
     */
    public function hold()
    {
        if (!auth()->user()->can('purchases.create'))
        return $this->response->setJSON([
            'status' => false,
            'message' => "Don't have permission to create this record!"
        ]);
        
        $model = new PurchaseModel();
        $purchasesItemModel = new PurchaseItemModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);

        unset($inputs['items']);

        $inputs['purchase_date'] = date('Y-m-d', strtotime($inputs['purchase_date']));

        $items = $this->request->getVar('items');
        if (!$items) return $this->response->setJSON(
            [
                'status' => false,
                'data' => null,
                'message' => "No product selected!",
                'input' => $inputs,
            ]
        );
        $id = $this->request->getPost('id');

        $res = [
            'status' => false,
            'data' => null,
            'message' => "Purchase couldn't be save!",
            'input' => $inputs,
        ];
        $purchases = $model->where('id', $id)->first();
        $this->db = Database::connect();

        if ($purchases) $res = array_merge($res, ['message' => "Purchase on hold!"]);
        else $res = array_merge($res, ['message' => "Purchase is placed on hold!"]);

        try {
            $this->db->transException(true)->transStart();
            if (empty($inputs['store_id'])) unset($inputs['store_id']);
            $saved = $model->save($inputs);
            if ($saved && !$purchases) {
                $id = $model->getInsertID();
                $purchasesItems = [];

                foreach ($items as $k => $row) {
                    $items[$k]['purchase_id'] = $id;
                    if (empty($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (empty($items[$k]['store_id']) && isset($inputs['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];
                    if (empty($items[$k]['store_id'])) unset($items[$k]['store_id']);
                    array_push($purchasesItems, $items[$k]);
                }
                $purchasesItemModel->insertBatch($purchasesItems);
            } else if ($saved) {
                $purchasesItems = [];
                $id = $purchases->id;
                $purchasesItemModel->builder()->where('purchase_id', $id)->delete();

                foreach ($items as $k => $row) {
                    $items[$k]['purchase_id'] = $id;
                    if (empty($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (empty($items[$k]['store_id']) && isset($inputs['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];
                    if (empty($items[$k]['store_id'])) unset($items[$k]['store_id']);
                    array_push($purchasesItems, $items[$k]);
                }
                $purchasesItemModel->insertBatch($purchasesItems, 'id');
            }

            $purchases = $model->find($id);
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            $res = array_merge($res, [
                'message' => $e->getMessage(),
            ]);
            return $this->response->setJSON($res);
        }
        if ($this->db->transStatus()) {
            $res = array_merge($res, [
                'status' => true,
                'data' => $model->find($id),
            ]);
        } else {
            $res = array_merge($res, ['status' => false]);
        }
        return $this->response->setJSON($res);
    }

    /**
     * return json for select2
     * @return Response- http response
     */
    public function select2(): Response
    {
        $inputs = $this->request->getVar();
        $model = new PurchaseModel();
        return $this->response->setJSON(toSelect2Result(
            $model,
            ['purchases.invoice', 'suppliers.name'],
            $inputs,
            'concat(purchases.invoice," (",suppliers.name," - GHS ",total_amount,")") as text,purchases.*',
            [
                ['table' => 'suppliers', 'cond' => 'suppliers.id=purchases.supplier_id', 'type' => 'inner'],
            ]
        ));
    }

        /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new PurchaseModel();

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

        /**
     * return json for datatables
     * @return Response - http response
     */
    public function daily_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new PurchaseModel();

        return $this->response->setJSON(toBuilderDatatableResult($model->getDailyWalkinReport(), $inputs));
    }

    /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('purchases.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new PurchaseModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Purchase deleted successfully!",
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
