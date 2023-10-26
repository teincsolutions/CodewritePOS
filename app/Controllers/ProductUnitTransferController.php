<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductUnitTransferModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use App\Models\UnitTransferItemModel;
use App\Models\UnitTransferModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;

class ProductUnitTransferController extends BaseController
{

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Product Unit Transfer List',
        ];
        return view('pages/transfers/list_product_unit_transfer', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $storeModel = new StoreModel();
        $model = new UnitTransferModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;

        $data = [
            'title' => 'Transfer Units',
            'stores' => $storeModel->where('status', 'opened')->findAll(),
            'invoice' => substr(time() + 1100000000 + $lastId, 0, 10),
        ];
        return view('pages/transfers/edit_product_unit_transfer', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Product Unit Transfer Details'
        ];
        $model = new UnitTransferModel();
        $data = array_merge($data, [
            'transfer' => $model->find($id),
        ]);
        return view('pages/transfers/show_product_unit_transfer', $data);
    }

        /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        $model = new UnitTransferModel();
        $transferItemModel = new UnitTransferItemModel();
        $stockModel = new StockModel();

        if (!auth()->user()->can('unit-transfers.create'))
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
                    $items[$k]['unit_transfer_id'] = $id;

                    array_push($transferItems, $items[$k]);
                    $stockWhere = [
                        'product_id' => $items[$k]['from_product_id'],
                        'store_id' => $inputs['store_id']
                    ];

                    if ($builder->where($stockWhere)->get()->getRowObject()) {
                        $builder->set('instock', '(instock - ' . $items[$k]['from_unit_qty'] . ')', false)
                            ->update(null, $stockWhere);
                    } else {
                        $builder->insert([
                            'product_id' => $items[$k]['from_product_id'],
                            'store_id' =>  $inputs['store_id'],
                            'instock' => (0 - $items[$k]['from_unit_qty'])
                        ]);
                    }
                    $stockWhere = [
                        'product_id' => $items[$k]['to_product_id'],
                        'store_id' => $inputs['store_id']
                    ];
                    if ($builder->where($stockWhere)->get()->getRowObject()) {
                        $builder->set('instock', '(instock + ' . $items[$k]['to_unit_qty'] . ')', false)
                            ->update(null, $stockWhere);
                    } else {
                        $builder->insert([
                            'product_id' => $items[$k]['to_product_id'],
                            'store_id' =>  $inputs['store_id'],
                            'instock' => $items[$k]['to_unit_qty']
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
        $model = new UnitTransferModel();
        return $this->response->setJSON(toDatatableResult($model,$inputs));
    }

     /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('unit-transfers.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new UnitTransferModel();
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
