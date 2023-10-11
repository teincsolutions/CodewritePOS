<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductTransferItemModel;
use App\Models\ProductTransferModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Psr\Log\LoggerInterface;

class ProductTransferController extends BaseController
{

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $storeModel = new StoreModel();

        $data = [
            'title' => 'Product Transfer List',
            'stores' => $storeModel->where('status','opened')->findAll(),
        ];
        return view('pages/transfers/list_product_transfer', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit()
    {
        $storeModel = new StoreModel();
        $model = new ProductTransferModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;

        $data = [
            'title' => 'Transfer Products',
            'stores' => $storeModel->where('status','opened')->findAll(),
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
        $id = $this->request->getPost('id');

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
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
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
