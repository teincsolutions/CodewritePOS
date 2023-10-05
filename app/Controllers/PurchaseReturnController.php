<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseItemModel;
use App\Models\PurchaseReturnModel;
use App\Models\StockModel;
use App\Models\SupplierLedgerModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Psr\Log\LoggerInterface;

class PurchaseReturnController extends BaseController
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
            'title' => 'Purchase Return List',
        ];
        return view('pages/purchase_returns/list_purchase_return', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Purchase Return'
        ];

        if ($id) {
            $model = new PurchaseReturnModel();
            $data = array_merge($data, [
                'purchase_return' => $model->find($id),
                'title' => 'Edit Purchase Return',
            ]);
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
            'purchase_return' => $model->find($id),
        ]);

        return view('pages/purchase_returns/show_purchase_return', $data);
    }


    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        $model = new PurchaseReturnModel();
        $purchaseItemModel = new PurchaseItemModel();
        $stockModel = new StockModel();
        $ledger = new SupplierLedgerModel();

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
                    $items[$k]['sale_id'] = $id;
                    if (empty($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (is_null($items[$k]['store_id']) || empty($items[$k]['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];

                    array_push($purchaseItems, $items[$k]);
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
                $purchaseItemModel->insertBatch($purchaseItems);
                $purchase = $model->find($id);
                if ($inputs['customer_id'])
                    $ledger->save([
                        'tdate' => $inputs['purchase_date'],
                        'customer_id' => $inputs['customer_id'],
                        'sale_id' => $purchase->id,
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
            $purchase = $model->find($id);
            $res = array_merge($res, [
                'status' => true,
                'data' => $purchase,
                'receipt' => view('pages/purchase/pos_receipt', ['purchase' => $purchase])
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
        return $this->response->setJSON(toDatatableResult($model, $inputs, [
            ['table' => 'purchase', 'cond' => 'purchase.id=purchase_returns.sale_id']
        ]));
    }
}
