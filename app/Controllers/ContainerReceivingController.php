<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ContainerReceivingItemModel;
use App\Models\ContainerReceivingModel;
use App\Models\ContainerStockModel;
use App\Models\CustomerContainerModel;
use App\Models\CustomerLedgerModel;
use App\Models\CustomerModel;
use App\Models\SalesModel;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;

class ContainerReceivingController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $stores = (new UserModel())->getMyStores();

        $data = [
            'title' => 'Container Receivings List',
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
            'stores' => $stores,
        ];
        return view('pages/container_receivings/list_container_receiving', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit()
    {
        $invoice = $this->request->getVar('invoice');
        $model = new ContainerReceivingModel();
        $saleModel = new SalesModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;
        $stores = (new UserModel())->getMyStores();

        $data = [
            'title' => 'Create Container Receivings',
            'invoice' => substr((time() + 1000000000) + $lastId, 0, 10),
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];
        $whereInvoice = [
            'invoice' => $invoice,
            'order_status' => 'completed'
        ];
        $sales = $saleModel->where($whereInvoice)->first();
        if ($invoice && $sales) {
            $data = array_merge($data, ['sales' => $sales]);
        } else if ($invoice) {
            $data = array_merge($data, ['error' => "This invoice doesn't exist or not completed!",]);
        }
        return view('pages/container_receivings/edit_container_receiving', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Container Receivings Details'
        ];
        $model = new ContainerReceivingModel();
        $data = array_merge($data, [
            'return' => $model->find($id),
        ]);

        return view('pages/container_receivings/invoice', $data);
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        if (!auth()->user()->can('container-receivings.create'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to create this record!"
            ]);

        $model = new ContainerReceivingModel();
        $returnItemModel = new ContainerReceivingItemModel();
        $stockModel = new ContainerStockModel();
        $ledger = new CustomerLedgerModel();
        $saleModel = new SalesModel();
        $custContainerModel = new CustomerContainerModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);
        $inputs['return_date'] = date('Y-m-d', strtotime($inputs['return_date']));

        $items = $this->request->getVar('items');
        if (!$items) return $this->response->setJSON(
            [
                'status' => false,
                'data' => null,
                'message' => "No container selected!",
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

        $res = array_merge($res, ['message' => "Container Receivingss created successfully!"]);

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);
            if ($saved) {
                $id = $model->getInsertID();
                $returnItems = [];
                $builder = $stockModel->builder();
                $cbuilder = $custContainerModel->builder();

                foreach ($items as $k => $row) {
                    $items[$k]['container_receiving_id'] = $id;
                    array_push($returnItems, $items[$k]);
                    $stockWhere = [
                        'container_id' => $items[$k]['container_id'],
                        'store_id' => $items[$k]['store_id']
                    ];

                    if ($builder->where($stockWhere)->get()->getRowObject()) {
                        $builder->set('instock', '(instock + ' . $items[$k]['qty'] . ')', false)
                            ->update(null, $stockWhere);
                    } else {
                        $builder->insert([
                            'container_id' => $items[$k]['container_id'],
                            'store_id' =>  $items[$k]['store_id'],
                            'instock' => $items[$k]['qty']
                        ]);
                    }

                    // receiving containers for customers
                    if ($inputs['type'] === 'customer') {
                        $custContWhere = array_merge($stockWhere, [
                            'container_id' => $items[$k]['container_id'],
                            'store_id' => $items[$k]['store_id'],
                            'customer_id' => $inputs['customer_id']
                        ]);
                        if ($cbuilder->where($custContWhere)->get()->getRowObject()) {
                            $cbuilder->set('instock', '(instock + ' . $items[$k]['qty'] . ')', false)
                                ->update(null, $custContWhere);
                        } else {
                            $cbuilder->insert([
                                'container_id' => $items[$k]['container_id'],
                                'store_id' =>  $items[$k]['store_id'],
                                'customer_id' => $inputs['customer_id'],
                                'instock' => $items[$k]['qty']
                            ]);
                        }
                    }
                }
                $returnItemModel->insertBatch($returnItems);
                if ($inputs['settlement'] === 'cash') {
                    $data = array_merge($inputs, ['sales_date' => $inputs['return_date']]);
                    $saleModel->save($data);

                    $data = [
                        'tdate' => $inputs['return_date'],
                        'customer_id' => $inputs['customer_id'],
                        'sale_id' => $saleModel->getInsertID(),
                        'store_id' => $inputs['store_id'],
                        'payment_type' => 'cash',
                        'ledger_type' => 'sales',
                        'credit' => $inputs['paid'],
                        'debit' => 0,
                        'user_id' => $inputs['user_id'],
                    ];
                    if ($inputs['paid'] > 0)  $ledger->save($data);
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
                'receipt' => view('pages/container_receivings/pos_receipt', ['returns' => $returns])
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
        $model = new ContainerReceivingModel();
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
                'receipt' =>  view('pages/container_receivings/pos_receipt', ['returns' => $return]),
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
        $model = new ContainerReceivingModel();
        $model->select('container_receivings.*');
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function stock_report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ContainerReceivingModel();
        $builder = $model->builder();
        $builder->select('container_receivings.*, sales.customer_id')
            ->selectSum('container_receivings_items.qty', 'qty')
            ->join('container_receivings_items', 'container_receivings_items.container_receiving_id=container_receivings.id')
            ->join('sales', 'sales.id=container_receivings.sale_id')
            ->where('container_id', $inputs['container_id'] ?? '')
            ->where('container_receivings.order_status', 'completed')
            ->groupBy('container_receivings.id');

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
        if (!auth()->user()->can('container-receivings.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new ContainerReceivingModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Container Receivings deleted successfully!",
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
