<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdjustmentItemModel;
use App\Models\ContainerAdjustmentItemModel;
use App\Models\ContainerStockAdjustmentModel;
use App\Models\ContainerStockModel;
use App\Models\StoreModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;

class ContainerAdjustmentController extends BaseController
{

    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Container Adjustment List',
        ];
        return view('pages/container_adjustments/list_adjustment', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit()
    {

        $model = new ContainerStockAdjustmentModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;
        $storeModel = new StoreModel();

        $data = [
            'title' => 'Create Container Adjustment',
            'invoice' => substr(time() + $lastId, 0, 10),
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];

        return view('pages/container_adjustments/edit_adjustment', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Container Adjustment Details'
        ];
        $model = new ContainerStockAdjustmentModel();

        $data = array_merge($data, [
            'adjustment' => $model->find($id),
        ]);

        return view('pages/container_adjustments/invoice', $data);
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        if (!auth()->user()->can('container-adjustments.create'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to create this record!"
            ]);

        $model = new ContainerStockAdjustmentModel();
        $adjustmentItemModel = new ContainerAdjustmentItemModel();
        $stockModel = new ContainerStockModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);

        $inputs['adj_date'] = date('Y-m-d', strtotime($inputs['adj_date']));
        $lastItem = $model->where('adj_date', $inputs['adj_date'])->orderBy('id', 'desc')->first();
        $inputs['invoice'] = $lastItem ? intval($lastItem->invoice) + 1 : date('ymd', strtotime($inputs['adj_date'])) . str_pad('1', 4, '0', STR_PAD_LEFT);

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
                    $items[$k]['container_adjustment_id'] = $id;
                    $items[$k]['store_id'] = $inputs['store_id'];

                    array_push($adjustmentItems, $items[$k]);
                    $stockWhere = [
                        'container_id' => $items[$k]['container_id'],
                        'store_id' => $items[$k]['store_id']
                    ];

                    if ($builder->where($stockWhere)->get()->getRowObject()) {
                        $builder->set('instock',  $items[$k]['qty'], false)
                            ->update(null, $stockWhere);
                    } else {
                        $builder->insert([
                            'container_id' => $items[$k]['container_id'],
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
               // 'receipt' => view('pages/container_adjustments/pos_receipt', ['adjustment' => $adjustment])
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
        $model = new ContainerStockAdjustmentModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

       /**
     * return json for datatables
     * @return Response - http response
     */
    public function stock_report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ContainerStockAdjustmentModel();
        $builder = $model->builder();
        $builder->select('container_stock_container_adjustments.*')
            ->selectSum('container_adjustment_items.qty', 'qty')
            ->selectSum('container_adjustment_items.instock_qty', 'instockQty')
            ->selectSum('(container_adjustment_items.qty - container_adjustment_items.instock_qty)', 'diffQty')
            ->join('container_adjustment_items', 'container_adjustment_items.adjustment_id=container_stock_container_adjustments.id')
            ->where('container_id', $inputs['container_id'] ?? '')
            ->groupBy('container_stock_container_adjustments.id');

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
        if (!auth()->user()->can('container_adjustments.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new ContainerStockAdjustmentModel();
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
