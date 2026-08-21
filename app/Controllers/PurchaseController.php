<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseItemModel;
use App\Models\PurchaseModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use App\Models\SupplierLedgerModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;


class PurchaseController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $supModel = new SupplierModel();
        $stores = (new UserModel())->getMyStores();

        $data = [
            'title' => 'Purchase List',
            'suppliers' => $supModel->findAll(),
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];

        return view('pages/purchases/list_purchase', $data);
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function daily_report_print()
    {
        $date = $this->request->getVar('date');
        $model = new PurchaseModel();
        $storeModel = new StoreModel();
        $res = [
            'status' => false,
            'data' => null,
            'message' => "Invoice not found!",
        ];
        $report = $model->getDailyReport(['purchase_date' => $date])->get()->getFirstRow();

        if ($report) {
            $report->store = $storeModel->where('id', $report->store_id)->first();
            $res = array_merge($res, [
                'status' => true,
                'data' => $report,
                'receipt' => view('pages/reports/daily_purchase_receipt', ['report' => $report]),
                'message' => "Invoice found!",
            ]);
        }
        return $this->response->setJSON($res);
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function daily_report()
    {
        $stores = (new UserModel())->getMyStores();

        $data = [
            'title' => 'Daily Purchase Report',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];

        return view('pages/reports/daily_purchases', $data);
    }

    public function print($id): Response
    {
        $model = new PurchaseModel();
        $purchase = $model->where('id', $id)->first();
        $res = [
            'status' => false,
            'data' => null,
            'message' => "Invoice not found!",
        ];

        if ($purchase) {
            $res = array_merge($res, [
                'status' => true,
                'data' => $purchase,
                'receipt' => view('pages/purchases/pos_receipt', ['purchases' => $purchase]),
                'message' => "Invoice found!",
            ]);
        }
        return $this->response->setJSON($res);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $model = new PurchaseModel();
        $lastItem = $model->where('purchase_date', date('ymd', time()))->orderBy('id', 'desc')->first();
        $invoiceNo = $lastItem ? intval($lastItem->invoice) + 1 : date('ymd', time()) . str_pad('1', 4, '0', STR_PAD_LEFT);
        $stores =  $stores = (new UserModel())->getMyStores();
        $supModel = new SupplierModel();

        $data = [
            'title' => 'Purchase Order',
            'invoice' => $invoiceNo,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
            'stores' => $stores,
            'suppliers' => $supModel->findAll(),
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
        $lastItem = $model->where('purchase_date', $inputs['purchase_date'])->orderBy('id', 'desc')->first();
        $inputs['invoice'] = $lastItem ? intval($lastItem->invoice) + 1 : date('ymd', strtotime($inputs['purchase_date'])) . str_pad('1', 4, '0', STR_PAD_LEFT);

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

        $res = array_merge($res, ['message' => "Purchase created successfully!"]);
        if ($purchases) {
            $model->delete($id);
            unset($inputs['id']);
        }

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);

            if ($saved) {
                $id = $model->getInsertID();
                $purchasesItems = [];
                $builder = $stockModel->builder();
                foreach ($items as $k => $row) {
                    $items[$k]['purchase_id'] = $id;
                    if (is_null($items[$k]['store_id']) || empty($items[$k]['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];

                    array_push($purchasesItems, $items[$k]);
                    $stockWhere = [
                        'product_id' => $items[$k]['product_id'],
                        'store_id' => $items[$k]['store_id']
                    ];

                    if ($builder->where($stockWhere)->get()->getRowObject()) {
                        $builder->set('instock', '(instock + ' . $items[$k]['qty'] . ')', false)
                            ->set('updated_at', date('Y-m-d H:i:s'))
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
     * return json for items datatables
     * @return Response - http response
     */
    public function itemsDatatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new PurchaseItemModel();

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return view for edit items
     * @return Response - http response
     */
    public function editItems($id = null)
    {
        $model = new PurchaseModel();
        $itemModel = new PurchaseItemModel();

        $stores = (new UserModel())->getMyStores();
        $supModel = new SupplierModel();

        $data = [
            'title' => 'Edit Purchase Items',
            'stores' => $stores,
            'suppliers' => $supModel->findAll(),
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];

        if ($id) {
            $purchase = $model->where('id', $id)->first();
            if ($purchase) {
                $data = array_merge($data, [
                    'purchase' => $purchase,
                    'items' => $itemModel->where('purchase_id', $id)->findAll(),
                    'title' => 'Edit Purchase Items - ' . $purchase->invoice,
                ]);
            } else {
                $data['error'] = "Purchase not found!";
            }
        }

        return view('pages/purchases/edit_items', $data);
    }

    /**
     * return json for save items
     * @return Response - http response
     */
    public function saveItems()
    {
        if (!auth()->user()->can('purchases.create'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to create this record!"
            ]);

        $model = new PurchaseModel();
        $itemModel = new PurchaseItemModel();
        $stockModel = new StockModel();
        $ledger = new SupplierLedgerModel();

        $inputs = $this->request->getVar();

        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);

        $id = $inputs['purchase_id'] ?? null;
        $items = $inputs['items'] ?? [];

        if (!$id || !$items) return $this->response->setJSON(
            [
                'status' => false,
                'data' => null,
                'message' => "Missing purchase ID or items!",
                'input' => $inputs,
            ]
        );

        $purchase = $model->where('id', $id)->first();
        if (!$purchase) return $this->response->setJSON(
            [
                'status' => false,
                'data' => null,
                'message' => "Purchase not found!",
            ]
        );

        $res = [
            'status' => false,
            'data' => null,
            'message' => "Items couldn't be saved!",
            'input' => $inputs,
        ];

        $this->db = Database::connect();

        try {
            $this->db->transException(true)->transStart();

            foreach ($items as $itemId => $row) {
                $subtotal = 0;
                if (!empty($row['qty']) && !empty($row['unit_price'])) {
                    $subtotal = floatval($row['qty']) * floatval($row['unit_price']);
                }
                if (!empty($row['discount'])) {
                    $subtotal -= floatval($row['discount']);
                }
                if (!empty($row['tax'])) {
                    $subtotal += floatval($row['tax']);
                }

                $updateData = [
                    'qty' => $row['qty'] ?? 0,
                    'unit_price' => $row['unit_price'] ?? 0,
                    'discount' => $row['discount'] ?? 0,
                    'tax' => $row['tax'] ?? 0,
                    'subtotal' => $subtotal,
                ];

                $itemModel->update($itemId, $updateData);
            }

            $this->db->transComplete();

            if ($this->db->transStatus()) {
                $purchase = $model->find($id);
                $items = $itemModel->where('purchase_id', $id)->findAll();

                $totalAmount = 0;
                foreach ($items as $item) {
                    $totalAmount += floatval($item->subtotal);
                }

                $res = array_merge($res, [
                    'status' => true,
                    'data' => [
                        'purchase' => $purchase,
                        'items' => $items,
                        'total_amount' => number_format($totalAmount, 2),
                    ],
                    'message' => "Items updated successfully!",
                ]);
            } else {
                $res = array_merge($res, ['status' => false]);
            }
        } catch (DatabaseException $e) {
            $this->db->transRollingBack();
            $res = array_merge($res, [
                'message' => $e->getMessage(),
            ]);
        }

        return $this->response->setJSON($res);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function daily_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new PurchaseModel();

        return $this->response->setJSON(toBuilderDatatableResult($model->getDailyReport(), $inputs));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function stock_report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new PurchaseModel();
        $builder = $model->builder();
        $builder->select('purchases.*')
            ->selectSum('purchase_items.qty', 'qty')
            ->join('purchase_items', 'purchase_items.purchase_id=purchases.id')
            ->where('product_id', $inputs['product_id'] ?? '')
            ->where('order_status', 'completed')
            ->groupBy('purchases.id');

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
