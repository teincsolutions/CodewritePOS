<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ContainerItemModel;
use App\Models\ContainerModel;
use App\Models\ContainerReturnItemModel;
use App\Models\ContainerReturnModel;
use App\Models\ContainerStockModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use App\Models\SupplierLedgerModel;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;

class ContainerReturnController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Container Return List',
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];
        return view('pages/container_returns/list_container_return', $data);
    }


    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit()
    {

        $model = new ContainerReturnModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;
        $stores = (new UserModel())->getMyStores();

        $data = [
            'title' => 'Create Container Return',
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];

        return view('pages/container_returns/edit_container_return', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Container Return Details'
        ];
        $model = new ContainerReturnModel();
        $data = array_merge($data, [
            'return' => $model->find($id),
        ]);

        return view('pages/container_returns/invoice', $data);
    }


    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        if (!auth()->user()->can('container-returns.create'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to create this record!"
            ]);

        $model = new ContainerReturnModel();
        $returnItemModel = new ContainerReturnItemModel();
        $stockContModel = new ContainerStockModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);

        $inputs['return_date'] = date('Y-m-d', strtotime($inputs['return_date']));
        $lastItem = $model->where('return_date', $inputs['return_date'])->orderBy('id', 'desc')->first();
        $inputs['invoice'] = $lastItem ? intval($lastItem->invoice) + 1 : date('ymd', strtotime($inputs['return_date'])) . str_pad('1', 4, '0', STR_PAD_LEFT);

        $items = $this->request->getVar('items');
        if (!$items || sizeof($items) === 0) return $this->response->setJSON(
            [
                'status' => false,
                'data' => null,
                'message' => "No container selected!",
                'input' => $inputs,
            ]
        );
        unset($inputs['items']);

        $id = $this->request->getPost('id');

        $res = [
            'status' => false,
            'data' => null,
            'message' => "Container couldn't be save!",
            'input' => $inputs,
        ];
        $return = $model->where('id', $id)->first();
        $this->db = Database::connect();

        if ($return) $res = array_merge($res, ['message' => "Container Return updated successfully!"]);
        else $res = array_merge($res, ['message' => "Container Return created successfully!"]);

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);

            if ($saved && !$return) {
                $id = $model->getInsertID();
                $returnItems = [];
                $cbuilder = $stockContModel->builder();
                foreach ($items as $k => $row) {
                    $items[$k]['container_return_id'] = $id;
                    array_push($returnItems, $items[$k]);
                    $stockWhere = [
                        'container_id' => $items[$k]['container_id'],
                        'store_id' => $items[$k]['store_id']
                    ];

                    if ($cbuilder->where($stockWhere)->get()->getRowObject()) {
                        $cbuilder->set('instock', '(instock - ' . $items[$k]['qty'] . ')', false)
                            ->update(null, $stockWhere);
                    } else {
                        $cbuilder->insert([
                            'container_id' => $items[$k]['container_id'],
                            'store_id' =>  $items[$k]['store_id'],
                            'instock' => 0 - $items[$k]['qty']
                        ]);
                    }
                }
                $returnItemModel->insertBatch($returnItems);
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
                'receipt' => view('pages/container_returns/pos_receipt', ['returns' => $return])
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
        $model = new ContainerReturnModel();
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
                'receipt' =>  view('pages/container_returns/pos_receipt', ['returns' => $return]),
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
        $model = new ContainerReturnModel();

        $model->select('container_returns.*');
        return $this->response->setJSON(toDatatableResult($model, $inputs,));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function stock_report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ContainerReturnModel();
        $cbuilder = $model->cbuilder();
        $cbuilder->select('container_returns.*')
            ->selectSum('container_returns_items.qty', 'qty')
            ->join('container_returns_items', 'container_returns_items.container_return_id=container_returns.id')
            ->join('purchases', 'purchases.id=container_returns.purchase_id')
            ->where('container_id', $inputs['container_id'] ?? '')
            ->where('container_returns.order_status', 'completed')
            ->groupBy('container_returns.id');

        return $this->response->setJSON(toBuilderDatatableResult($cbuilder, $inputs, function ($item) {
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
        if (!auth()->user()->can('container-returns.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new ContainerReturnModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Container Return deleted successfully!",
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
