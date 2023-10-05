<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerLedgerModel;
use App\Models\SalesModel;
use App\Models\SalesReturnItemModel;
use App\Models\SalesReturnModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Psr\Log\LoggerInterface;

class SalesReturnController extends BaseController
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

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Sales Return List',
        ];
        return view('pages/sales_returns/list_sales_return', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $model = new SalesReturnModel();
        $saleModel = new SalesModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;
        $storeModel = new StoreModel();

        $data = [
            'title' => 'Point of Sales',
            'invoice' => substr(time() + $lastId, 0, 10),
            'stores' => $storeModel->findAll(),
        ];
        if ($id) {
            $model = new SalesReturnModel();
            $data = array_merge($data, [
                'sales_return' => $model->find($id),
                'title' => 'Edit Sales Return',
            ]);
        }
        return view('pages/sales_returns/edit_sales_return', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Sales Return Details'
        ];
        $model = new SalesReturnModel();
        $data = array_merge($data, [
            'sales_return' => $model->find($id),
        ]);

        return view('pages/sales_returns/show_sales_return', $data);
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        $model = new SalesReturnModel();
        $salesItemModel = new SalesReturnItemModel();
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
                        $builder->set('instock', '(instock + ' . $items[$k]['qty'] . ')', false)
                            ->update(null, $stockWhere);
                    } else {
                        $builder->insert([
                            'product_id' => $items[$k]['product_id'],
                            'store_id' =>  $items[$k]['store_id'],
                            'instock' => $items[$k]['qty']
                        ]);
                    }
                }
                $salesItemModel->insertBatch($salesItems);
                $sales = $model->find($id);
                if ($inputs['customer_id'])
                    $ledger->save([
                        'tdate' => $inputs['sales_date'],
                        'customer_id' => $inputs['customer_id'],
                        'sale_id' => $sales->id,
                        'payment_type' => $inputs['payment_type'],
                        'ledger_type' => 'returns',
                        'credit' => $inputs['total_amount'],
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
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new SalesReturnModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs, [
            ['table' => 'sales', 'cond' => 'sales.id=sales_returns.sale_id']
        ]));
    }
}
