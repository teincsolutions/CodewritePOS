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
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Sales Return List',
            'stores' => $storeModel->where('status','opened')->findAll(),
        ];
        return view('pages/sales_returns/list_sales_return', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit()
    {
        $invoice = $this->request->getVar('invoice');
        $model = new SalesReturnModel();
        $saleModel = new SalesModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;
        $storeModel = new StoreModel();

        $data = [
            'title' => 'Create Sales Return',
            'invoice' => substr((time() + 1000000000) + $lastId, 0, 10),
            'stores' => $storeModel->where('status','opened')->findAll(),
        ];
        $whereInvoice = [
            'invoice' => $invoice,
            'order_status' => 'completed'
        ];
        $sales = $saleModel->where($whereInvoice)->first();
        if ($invoice && $sales) {
            $data = array_merge($data, ['sales' => $sales]);
        } else if($invoice) {
            $data = array_merge($data, ['error' => "This invoice doesn't exist or not completed!",]);
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
        $returnItemModel = new SalesReturnItemModel();
        $stockModel = new StockModel();
        $ledger = new CustomerLedgerModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);
        unset($inputs['items']);
        $inputs['return_date'] = date('Y-m-d', strtotime($inputs['return_date']));

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
        $this->db = Database::connect();

        $res = array_merge($res, ['message' => "Sales Returns created successfully!"]);

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);
            if ($saved) {
                $id = $model->getInsertID();
                $returnItems = [];
                $builder = $stockModel->builder();
                foreach ($items as $k => $row) {
                    $items[$k]['sales_return_id'] = $id;
                    if (empty($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (is_null($items[$k]['store_id']) || empty($items[$k]['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];

                    array_push($returnItems, $items[$k]);
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
                $returnItemModel->insertBatch($returnItems);
                if ($inputs['customer_id']){
                    $ledger->save([
                        'tdate' => $inputs['return_date'],
                        'customer_id' => $inputs['customer_id'],
                        'sale_id' => $inputs['sale_id'],
                        'sales_return_id' => $id,
                        'payment_type' => 'cash',
                        'ledger_type' => 'returns',
                        'credit' => $inputs['total_amount'],
                        'debit' => $inputs['paid'],
                        'user_id' => isset($inputs['user_id']) ? $inputs['user_id'] : null,
                    ]);
                    $saleModel = new SalesModel();
                    $saleModel->updatePaymentStatus($inputs['sale_id']);
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
            $returns = $model->find($id);
            $res = array_merge($res, [
                'status' => true,
                'data' => $returns,
                'receipt' => view('pages/sales_returns/pos_receipt', ['returns' => $returns])
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
        $model->select('sales_returns.*');
        $model->join('sales', 'sales.id=sales_returns.sale_id');
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        $model = new SalesReturnModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Sales Return deleted successfully!",
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
