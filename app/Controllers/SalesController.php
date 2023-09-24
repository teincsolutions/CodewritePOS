<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\SalesItemModel;
use App\Models\SalesModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\RawSql;
use CodeIgniter\HTTP\Response;
use Config\Database;

use function PHPUnit\Framework\isNull;

class SalesController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Sales List',
        ];
        return view('pages/sales/list_sales', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function pos($id = null)
    {
        $model = new SalesModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;
        $storeModel = new StoreModel();
        $cusModel = new CustomerModel();
        $data = [
            'title' => 'Point of Sales',
            'invoice' => date('ymd') . str_pad($lastId + 1 % 10000, 4, "0", STR_PAD_LEFT),
            'stores' => $storeModel->findAll(),
            'customers' => $cusModel->findAll(),
        ];

        if ($id) {
            $data = array_merge($data, [
                'sale' => $model->where('id', $id)->first(),
                'title' => 'POS',
            ]);
        }
        return view('pages/sales/pos', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Sales Details'
        ];
        $model = new SalesModel();
        $data = array_merge($data, [
            'sale' => $model->find($id),
        ]);

        return view('pages/sales/show_sale', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new SalesModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        $model = new SalesModel();
        $salesItemModel = new SalesItemModel();
        $stockModel = new StockModel();

        $inputs = $this->request->getVar();
        unset($inputs['items']);

        $inputs['sales_date'] = date('Y-m-d', strtotime($inputs['sales_date']));

        $items = $this->request->getVar('items');
        $id = $this->request->getPost('id');

        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $sales = $model->where('id', $id)->first();
        $this->db = Database::connect();

        if ($sales) {
            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Sales updated successfully!",
                    'data' => $model->find($id),
                ]);
            } else {
                $res = array_merge($res, [
                    'status' => false,
                    'message' => "Couldn't be updated!"
                ]);
            }
        } else {


            try {
                $this->db->transException(true)->transStart();
                $sale = $model->save($inputs, true);
                $id = $model->getInsertID();
                if ($sale) {
                    $salesItems = [];
                    $builder = $stockModel->builder();

                    foreach ($items as $k => $row) {
                        $items[$k]['sale_id'] = $id;
                        if (isNull($items[$k]['tax_id']))
                            $items[$k]['tax_id'] = null;
                        array_push($salesItems, $items[$k]);
                        $builder->where([
                            'product_id' => $items[$k]['product_id'],
                            'store_id' => $items[$k]['store_id']
                        ]);
                        $builder->set('instock', 'instock - ' . $items[$k]['qty'],false);
                        $builder->update();
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
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Sales created successfully!",
                    'data' => $model->find($model->getInsertID()),
                ]);
            } else {
                $res = array_merge($res, [
                    'status' => false,
                    'message' => "Couldn't be created!"
                ]);
            }
        }
        return $this->response->setJSON($res);
    }

    /**
     * return jwon for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        $model = new SalesModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Sales deleted successfully!",
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
