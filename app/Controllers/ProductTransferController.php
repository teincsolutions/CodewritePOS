<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductTransferItemModel;
use App\Models\ProductTransferModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;

class ProductTransferController extends BaseController
{

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $stores = (new UserModel())->getMyStores();

        $data = [
            'title' => 'Product Transfer List',
            'stores' => $stores,
        ];
        return view('pages/transfers/list_product_transfer', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit()
    {
        $stores = (new UserModel())->getMyStores();

        $model = new ProductTransferModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;

        $data = [
            'title' => 'Transfer Products',
            'stores' => $stores,
            'invoice' => substr(time() + 2000000000 + $lastId, 0, 10),
        ];
        return view('pages/transfers/edit_product_transfer', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Product Transfer Details'
        ];
        $model = new ProductTransferModel();
        $data = array_merge($data, [
            'transfer' => $model->find($id),
        ]);

        return view('pages/transfers/show_product_transfer', $data);
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        $model = new ProductTransferModel();
        $transferItemModel = new ProductTransferItemModel();
        $stockModel = new StockModel();

        if (!auth()->user()->can('product-transfers.create'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to create this record!"
            ]);

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);

        unset($inputs['items']);
        $inputs['transfer_date'] = date('Y-m-d', strtotime($inputs['transfer_date']));

        $items = $this->request->getVar('items');
        if (!$items) return $this->response->setJSON(
            [
                'status' => false,
                'data' => null,
                'message' => "No product selected!",
                'input' => $inputs,
            ]
        );
        $res = [
            'status' => false,
            'data' => null,
            'message' => "Transfer couldn't be save!",
            'input' => $inputs,
        ];
        $this->db = Database::connect();
        $res = array_merge($res, ['message' => "Transfer created successfully!"]);

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);

            if ($saved) {
                $id = $model->getInsertID();
                $transferItems = [];
                $builder = $stockModel->builder();
                foreach ($items as $k => $row) {
                    $items[$k]['product_transfer_id'] = $id;
                    if (empty($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    $items[$k]['from_store_id'] = $inputs['from_store_id'];
                    $items[$k]['to_store_id'] = $inputs['to_store_id'];

                    array_push($transferItems, $items[$k]);
                    $stockWhere = [
                        'product_id' => $items[$k]['product_id'],
                        'store_id' => $items[$k]['from_store_id']
                    ];

                    if ($builder->where($stockWhere)->get()->getRowObject()) {
                        $builder->set('instock', '(instock - ' . $items[$k]['qty'] . ')', false)
                            ->update(null, $stockWhere);
                    } else {
                        $builder->insert([
                            'product_id' => $items[$k]['product_id'],
                            'store_id' =>  $items[$k]['from_store_id'],
                            'instock' => (0 - $items[$k]['qty'])
                        ]);
                    }
                    $stockWhere = [
                        'product_id' => $items[$k]['product_id'],
                        'store_id' => $items[$k]['to_store_id']
                    ];
                    if ($builder->where($stockWhere)->get()->getRowObject()) {
                        $builder->set('instock', '(instock + ' . $items[$k]['qty'] . ')', false)
                            ->set('updated_at', date('Y-m-d H:i:s'))
                            ->update(null, $stockWhere);
                    } else {
                        $builder->insert([
                            'product_id' => $items[$k]['product_id'],
                            'store_id' =>  $items[$k]['to_store_id'],
                            'instock' => $items[$k]['qty']
                        ]);
                    }
                }
                $transferItemModel->insertBatch($transferItems);
            }
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            $res = array_merge($res, [
                'message' => $e->getMessage(),
            ]);
            return $this->response->setJSON($res);
        }
        if ($this->db->transStatus()) {
            $transfer = $model->find($id);
            $res = array_merge($res, [
                'status' => true,
                'data' => $transfer,
                // 'receipt' => view('pages/transfers/pos_receipt1', ['transfer' => $transfer])
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
        $model = new ProductTransferModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function stock_report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ProductTransferModel();
        $builder = $model->builder();
        $builder->select('product_transfers.*')
            ->selectSum('product_transfer_items.qty', 'qty')
            ->join('product_transfer_items', 'product_transfer_items.product_transfer_id=product_transfers.id')
            ->where('product_id', $inputs['product_id'] ?? '')
            ->where('order_status', 'completed')
            ->groupBy('product_transfers.id');

        return $this->response->setJSON(toBuilderDatatableResult($builder, $inputs, function ($item) {
            $item->toStore = model('StoreModel')->where('id', $item->to_store_id)->first();
            $item->fromStore = model('StoreModel')->where('id', $item->from_store_id)->first();
            return $item;
        }));
    }

    /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('product-transfers.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new ProductTransferModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Transfer deleted successfully!",
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
