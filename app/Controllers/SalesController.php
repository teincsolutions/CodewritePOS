<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerLedgerModel;
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
        $cusModel = new CustomerModel();
        $data = [
            'title' => 'Sales List',
            'customers' => $cusModel->findAll(),
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
            'invoice' => substr(time() + $lastId, 0, 10),
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
        $ledger = new CustomerLedgerModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = auth()->user()->id;

        unset($inputs['items']);

        $inputs['sales_date'] = date('Y-m-d', strtotime($inputs['sales_date']));

        $items = $this->request->getVar('items');
        $id = $this->request->getPost('id');

        $res = [
            'status' => false,
            'data' => null,
            'message' => "Sales couldn't be save!",
            'input' => $inputs,
        ];
        $sales = $model->where('id', $id)->first();
        $this->db = Database::connect();

        if ($sales) $res = array_merge($res, ['message' => "Sales updated successfully!"]);
        else $res = array_merge($res, ['message' => "Sales created successfully!"]);

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);
            $id = $model->getInsertID();
            if ($saved && !$sales) {
                $salesItems = [];
                $builder = $stockModel->builder();

                foreach ($items as $k => $row) {
                    $items[$k]['sale_id'] = $id;
                    if (isNull($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (isNull($items[$k]['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];

                    array_push($salesItems, $items[$k]);
                    if ($builder->get()->getRowObject()) {
                        $builder->where([
                            'product_id' => $items[$k]['product_id'],
                            'store_id' => $items[$k]['store_id']
                        ]);
                        $builder->set('instock', '(instock - ' . $items[$k]['qty'].')', false);
                        $builder->update();
                    } else {
                        $builder->insert([
                            'product_id' => $items[$k]['product_id'],
                            'store_id' =>  $items[$k]['store_id'],
                            'instock' => (0 - $items[$k]['qty'])
                        ]);
                    }
                }
                $salesItemModel->insertBatch($salesItems);
                $sales = $model->find($id);
                if ($inputs['customer_id'])
                    $ledger->save([
                        'customer_id' => $inputs['customer_id'],
                        'sale_id' => $sales->id,
                        'debit' => $sales->total_amount,
                        'credit' => $sales->paid,
                        'user_id' => isset($inputs['user_id']) ? $inputs['user_id'] : null,
                    ]);
            } else if ($saved) {
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
                'data' => $model->find($id),
            ]);
        } else {
            $res = array_merge($res, ['status' => false]);
        }
        return $this->response->setJSON($res);
    }

    /**
     * return json for delete
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
