<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerLedgerModel;
use App\Models\CustomerModel;
use App\Models\SalesItemModel;
use App\Models\SalesModel;
use App\Models\SalesReturnModel;
use App\Models\StockModel;
use App\Models\StoreModel;
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
        $storeModel = new StoreModel();

        $data = [
            'title' => 'Sales List',
            'customers' => $cusModel->findAll(),
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];

        return view('pages/sales/list_sales', $data);
    }

     /**
     * return view for list
     * @return Response - http response
     */
    public function daily_report()
    {
        $cusModel = new CustomerModel();
        $storeModel = new StoreModel();

        $data = [
            'title' => 'Daily Sales Report',
            'stores' => $storeModel->where('status', 'opened')->findAll(),
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
        $storeModel = new StoreModel();
        $ledgerModel = new CustomerLedgerModel();
        $returnModel = new SalesReturnModel();

        $saleWhere = ['sales_date' => date('Y-m-d', time()), 'user_id' => (auth()->user()->id ?? 0)];
        $returnWhere = ['return_date' => date('Y-m-d', time()), 'user_id' => (auth()->user()->id ?? 0)];
        $holdWhere = ['order_status' => 'pending', 'user_id' => (auth()->user()->id ?? 0)];
        $ledgerWhere = [
            'tdate' => date('Y-m-d', time()),
            'user_id' => (auth()->user()->id ?? 0),
            'ledger_type' => 'sales'
        ];

        $data = [
            'title' => 'Point of Sales',
            'invoice' => substr(time() + $lastId, 0, 10),
            'stores' => $storeModel->where('status', 'opened')->findAll(),
            'saleList' => $model->where($saleWhere)->findAll(),
            'returnList' => $returnModel->where($returnWhere)->findAll(),
            'ledgerList' => $ledgerModel->where($ledgerWhere)->findAll(),
            'salesOnHold' =>  $model->where($holdWhere)->findAll(),
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

        if ($sales) $res = array_merge($res, ['message' => "Sales updated successfully!"]);
        else $res = array_merge($res, ['message' => "Sales created successfully!"]);

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);

            if ($saved && !$sales) {
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

        return $this->response->setJSON(toBuilderDatatableResult($model->getDailyWalkinReport(), $inputs));
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
