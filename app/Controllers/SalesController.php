<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerLedgerModel;
use App\Models\CustomerModel;
use App\Models\SalesItemModel;
use App\Models\SalesModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;


class SalesController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $cusModel = new CustomerModel();
        $stores = (new UserModel())->getMyStores();

        $data = [
            'title' => 'Sales List',
            'customers' => $cusModel->findAll(),
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];

        return view('pages/sales/list_sales', $data);
    }

    /**
     * return view for list
     * @return Response - http response
     */
    public function daily_report_print()
    {
        $date = $this->request->getVar('date');
        $model = new SalesModel();
        $storeModel = new StoreModel();
        $res = [
            'status' => false,
            'data' => null,
            'message' => "Invoice not found!",
        ];
        $report = $model->getDailyReport(['sales_date' => $date])->get()->getFirstRow();

        if ($report) {
            $report->store = $storeModel->where('id', $report->store_id)->first();
            $res = array_merge($res, [
                'status' => true,
                'data' => $report,
                'receipt' => view('pages/reports/daily_sale_receipt', ['report' => $report]),
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
            'title' => 'Daily Sales Report',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];

        return view('pages/reports/daily_sales', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function pos($id = null)
    {
        $model = new SalesModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;
        $stores = (new UserModel())->getMyStores();


        $data = [
            'title' => 'Point of Sales',
            'invoice' => substr(time() + $lastId, 0, 10),
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];

        if ($id) {
            $sales = $model->where(['id' => $id, 'order_status' => 'pending'])->first();
            $data = array_merge($data, [
                'sale' => $model->where('id', $id)->first(),
                'title' => 'Ponit of Sales - Resume',
                'sales' => $sales,
            ]);
            if (!$sales)  $data = array_merge($data, [
                'error' => "This sales doesn't exist or may not be hold!",
            ]);
        }
        return view('pages/sales/pos', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Sales Details'
        ];
        $model = new SalesModel();
        $data = array_merge($data, [
            'sales' => $model->find($id),
        ]);

        return view('pages/sales/invoice', $data);
    }

    /**
     * return json for receipt
     */
    public function print($id): Response
    {
        $model = new SalesModel();
        $sale = $model->where('id', $id)->first();
        $res = [
            'status' => false,
            'data' => null,
            'message' => "Invoice not found!",
        ];

        if ($sale) {
            $res = array_merge($res, [
                'status' => true,
                'data' => $sale,
                'receipt' => view('pages/sales/pos_receipt', ['sales' => $sale]),
                'message' => "Invoice found!",
            ]);
        }
        return $this->response->setJSON($res);
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        if (!auth()->user()->can('sales.create'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to create this record!"
            ]);

        $model = new SalesModel();
        $salesItemModel = new SalesItemModel();
        $stockModel = new StockModel();
        $ledger = new CustomerLedgerModel();
        $cusModel = new CustomerModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);

        unset($inputs['items']);

        $inputs['sales_date'] = date('Y-m-d', strtotime($inputs['sales_date']));

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
            'message' => "Sales couldn't be save!",
            'input' => $inputs,
        ];
        $sales = $model->where('id', $id)->first();
        $this->db = Database::connect();
        $res = array_merge($res, ['message' => "Sales created successfully!"]);
        if ($sales) {
            $model->delete($id);
            unset($inputs['id']);
        }

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);

            if ($saved) {
                $id = $model->getInsertID();
                $salesItems = [];
                $builder = $stockModel->builder();
                foreach ($items as $k => $row) {
                    $items[$k]['sale_id'] = $id;
                    if (empty($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (is_null($items[$k]['store_id']) || empty($items[$k]['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];

                    array_push($salesItems, $items[$k]);
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
                            'instock' => (0 - $items[$k]['qty'])
                        ]);
                    }
                }
                $salesItemModel->insertBatch($salesItems);
                $sales = $model->find($id);
                if ($inputs['customer_id']) {
                    $ledger->save([
                        'tdate' => $inputs['sales_date'],
                        'customer_id' => $inputs['customer_id'],
                        'store_id' => $inputs['store_id'],
                        'sale_id' => $sales->id,
                        'ledger_type' => 'sales',
                        'payment_type' => $inputs['payment_type'],
                        'debit' => $inputs['total_amount'],
                        'credit' => ($inputs['payment_status'] === 'paid' ? $inputs['total_amount'] : $inputs['paid']),
                        'user_id' => isset($inputs['user_id']) ? $inputs['user_id'] : null,
                    ]);
                    $model->updatePaymentStatus($id);

                    $customer = $cusModel->where('id', $inputs['customer_id'])->first();

                    if (setting('App.AllowCustomerLimit') === 'yes') {
                       
                        $balance = abs($customer->balance + ($inputs['paid'] - $inputs['total_amount']));
                        $days = $model->customerLatestDays($customer->id);
                        $customer->credit_limit_days = $customer->credit_limit_days ?? setting('App.LimitSalesDebitDays');

                        if ($balance > $customer->credit_limit &&  $balance > abs($customer->balance))
                            return $this->response->setJSON(
                                [
                                    'status' => false,
                                    'data' => null,
                                    'message' => "Customer has exceeded his/her Credit Limit of GHS "
                                        . number_format($customer->credit_limit, 2)
                                        . " with a balance of GHS " . number_format($balance, 2),
                                    'input' => $inputs,
                                ]
                            );
                        // limit to allowed days
                        if (setting('App.LimitSalesDebitDays') === 'yes')
                            if ($days !== null && intval($days) > $customer->credit_limit_days &&  $balance > abs($customer->balance))
                                return $this->response->setJSON(
                                    [
                                        'status' => false,
                                        'data' => null,
                                        'message' => "Customer has exceeded his/her Credit Days Limit of $customer->credit_limit_days days. It's been $days days since last credit sales.",
                                        'input' => $inputs,
                                    ]
                                );
                    }
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
            $sales = $model->find($id);
            $res = array_merge($res, [
                'status' => true,
                'data' => $sales,
                'receipt' => view('pages/sales/pos_receipt', ['sales' => $sales])
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
        if (!auth()->user()->can('sales.create'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to create this record!"
            ]);

        $model = new SalesModel();
        $salesItemModel = new SalesItemModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);

        unset($inputs['items']);

        $inputs['sales_date'] = date('Y-m-d', strtotime($inputs['sales_date']));

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
            'message' => "Sales couldn't be save!",
            'input' => $inputs,
        ];
        $sales = $model->where('id', $id)->first();
        $this->db = Database::connect();

        if ($sales) $res = array_merge($res, ['message' => "Sales on hold!"]);
        else $res = array_merge($res, ['message' => "Sales is placed on hold!"]);

        try {
            $this->db->transException(true)->transStart();
            if (empty($inputs['store_id'])) unset($inputs['store_id']);
            $saved = $model->save($inputs);
            if ($saved && !$sales) {
                $id = $model->getInsertID();
                $salesItems = [];

                foreach ($items as $k => $row) {
                    $items[$k]['sale_id'] = $id;
                    if (empty($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (empty($items[$k]['store_id']) && isset($inputs['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];
                    if (empty($items[$k]['store_id'])) $items[$k]['store_id'] = null;
                    array_push($salesItems, $items[$k]);
                }
                $salesItemModel->insertBatch($salesItems);
            } else if ($saved) {
                $id = $sales->id;
                $salesItemModel->builder()->delete(['sale_id' => $id]);

                $salesItems = [];
                foreach ($items as $k => $row) {
                    $items[$k]['sale_id'] = $id;
                    if (empty($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (empty($items[$k]['store_id']) && isset($inputs['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];
                    if (empty($items[$k]['store_id'])) $items[$k]['store_id'] = null;
                    array_push($salesItems, $items[$k]);
                }
                $salesItemModel->insertBatch($salesItems);
            }

            $sales = $model->find($id);
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
        $model = new SalesModel();
        return $this->response->setJSON(toSelect2Result(
            $model,
            ['sales.invoice', 'customers.name'],
            $inputs,
            'concat(sales.invoice," (",ifnull(customers.name,"walk-in-customer")," - GHS ",total_amount,")") as text,sales.*',
            [
                ['table' => 'customers', 'cond' => 'customers.id=sales.customer_id', 'type' => 'left'],
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
        $model = new SalesModel();

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }


    /**
     * return json for datatables
     * @return Response - http response
     */
    public function daily_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new SalesModel();

        return $this->response->setJSON(toBuilderDatatableResult($model->getDailyReport(), $inputs));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function stock_report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new SalesModel();
        $builder = $model->builder();
        $builder->select('sales.*')
            ->selectSum('sales_items.qty', 'qty')
            ->join('sales_items', 'sales_items.sale_id=sales.id')
            ->where('product_id', $inputs['product_id'] ?? '')
            ->where('order_status', 'completed')
            ->groupBy('sales.id');

        return $this->response->setJSON(toBuilderDatatableResult($builder, $inputs, function ($item) {
            $item->customer = model('CustomerModel')->where('id', $item->customer_id)->first();
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
        if (!auth()->user()->can('sales.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new SalesModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Sales deleted successfully!",
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
