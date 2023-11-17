<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdjustmentItemModel;
use App\Models\StockAdjustmentModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;

class AdjustmentController extends BaseController
{

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $stores =(new UserModel())->getMyStores();
        $data = [
            'title' => 'Adjustment List',
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
            'stores' => $stores,
        ];
        return view('pages/adjustments/list_adjustment', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit()
    {

        $model = new StockAdjustmentModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;
        $stores =(new UserModel())->getMyStores();

        $data = [
            'title' => 'Create Adjustment',
            'invoice' => substr(time() + $lastId, 0, 10),
            'stores' => $stores,
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ];

        return view('pages/adjustments/edit_adjustment', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Adjustment Details'
        ];
        $model = new StockAdjustmentModel();

        $data = array_merge($data, [
            'adjustment' => $model->find($id),
            'context' => 'user:' . user_id(),
            'settings' => service('settings'),
        ]);

        return view('pages/adjustments/invoice', $data);
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        if (!auth()->user()->can('adjustments.create'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to create this record!"
            ]);

        $model = new StockAdjustmentModel();
        $adjustmentItemModel = new AdjustmentItemModel();
        $stockModel = new StockModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);

        $inputs['adj_date'] = date('Y-m-d', strtotime($inputs['adj_date']));
        $items = $this->request->getVar('items');
        if (!$items || sizeof($items) === 0) return $this->response->setJSON(
            [
                'status' => false,
                'data' => null,
                'message' => "No product selected!",
                'input' => $inputs,
            ]
        );
        unset($inputs['items']);

        $res = [
            'status' => false,
            'data' => null,
            'message' => "Adjustment couldn't be save!",
            'input' => $inputs,
        ];
        $this->db = Database::connect();
        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);

            if ($saved) {
                $id = $model->getInsertID();
                $adjustmentItems = [];
                $builder = $stockModel->builder();
                foreach ($items as $k => $row) {
                    $items[$k]['adjustment_id'] = $id;
                    $items[$k]['store_id'] = $inputs['store_id'];

                    array_push($adjustmentItems, $items[$k]);
                    $stockWhere = [
                        'product_id' => $items[$k]['product_id'],
                        'store_id' => $items[$k]['store_id']
                    ];

                    if ($builder->where($stockWhere)->get()->getRowObject()) {
                        $builder->set('instock',  $items[$k]['qty'], false)
                            ->update(null, $stockWhere);
                    } else {
                        $builder->insert([
                            'product_id' => $items[$k]['product_id'],
                            'store_id' =>  $items[$k]['store_id'],
                            'instock' => $items[$k]['qty']
                        ]);
                    }
                }
                $adjustmentItemModel->insertBatch($adjustmentItems);
            }
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            $res = array_merge($res, [
                'message' => $e->getMessage(),
            ]);
            return $this->response->setJSON($res);
        }
        if ($this->db->transStatus()) {
            $adjustment = $model->find($id);
            $res = array_merge($res, [
                'status' => true,
                'message' => "Adjustment created successfully!",
                'data' => $adjustment,
               // 'receipt' => view('pages/adjustments/pos_receipt', ['adjustment' => $adjustment])
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
        $model = new StockAdjustmentModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

       /**
     * return json for datatables
     * @return Response - http response
     */
    public function stock_report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new StockAdjustmentModel();
        $builder = $model->builder();
        $builder->select('stock_adjustments.*')
            ->selectSum('adjustments_items.qty', 'qty')
            ->selectSum('adjustments_items.instock_qty', 'qtyInstock')
            ->selectSum('(adjustments_items.qty - adjustments_items.instock_qty)', 'diffQty')
            ->join('adjustments_items', 'adjustments_items.adjustment_id=stock_adjustments.id')
            ->where('product_id', $inputs['product_id'] ?? '')
            ->groupBy('stock_adjustments.id');

        return $this->response->setJSON(toBuilderDatatableResult($builder, $inputs, function ($item) {
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
        if (!auth()->user()->can('adjustments.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new StockAdjustmentModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Adjustment deleted successfully!",
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
