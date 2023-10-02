<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PurchaseItemModel;
use App\Models\PurchaseModel;
use App\Models\StockModel;
use App\Models\StoreModel;
use App\Models\SupplierModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Psr\Log\LoggerInterface;

use function PHPUnit\Framework\isNull;

class PurchaseController extends BaseController
{
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        if(!auth()->loggedIn()){
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
            'title' => 'Purchase List',
        ];
        return view('pages/purchases/list_purchase', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $model = new PurchaseModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $inv = $lastItem ? $lastItem->id : 1;
        $storeModel = new StoreModel();
        $suplModel = new SupplierModel();
        $data = [
            'title' => 'Add Purchase',
            'invoice' => substr(time() + $inv, 0, 10),
            'stores' => $storeModel->findAll(),
            'customers' => $suplModel->findAll(),
        ];

        if ($id) {
            $data = array_merge($data, [
                'purchase' => $model->where('id', $id)->first(),
                'title' => 'Edit Purchase',
            ]);
        }
        return view('pages/purchases/edit_purchase', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Purchase Details'
        ];
        $model = new PurchaseModel();
        $data = array_merge($data, [
            'purchase' => $model->find($id),
        ]);

        return view('pages/purchases/show_purchase', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new PurchaseModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        $model = new PurchaseModel();
        $purchaseItemModel = new PurchaseItemModel();
        $stockModel = new StockModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = auth()->user()->id;

        unset($inputs['items']);

        $inputs['purchase_date'] = date('Y-m-d', strtotime($inputs['purchase_date']));

        $items = $this->request->getVar('items');
        $id = $this->request->getPost('id');

        $res = [
            'status' => false,
            'data' => null,
            'message' => "Couldn't be save!",
            'input' => $inputs,
        ];
        $purchase = $model->where('id', $id)->first();
        $this->db = Database::connect();

        if ($purchase) $res = array_merge($res, ['message' => "Purchase updated successfully!"]);
        else $res = array_merge($res, ['message' => "Purchase created successfully!"]);

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);
            $id = $model->getInsertID();

            if ($saved && !$purchase) {
                $purchaseItems = [];
                $builder = $stockModel->builder();

                foreach ($items as $k => $row) {
                    $items[$k]['purchase_id'] = $id;
                    if (isNull($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (isNull($items[$k]['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];

                    array_push($purchaseItems, $items[$k]);
                    if ($builder->get()->getRowObject()) {
                        $builder->where([
                            'product_id' => $items[$k]['product_id'],
                            'store_id' =>  $items[$k]['store_id']
                        ]);
                        $builder->set('instock', 'instock + ' . $items[$k]['qty'], false);
                        $builder->update();
                    } else {
                        $builder->insert([
                            'product_id' => $items[$k]['product_id'],
                            'store_id' =>  $items[$k]['store_id'],
                            'instock' => $items[$k]['qty']
                        ]);
                    }
                }
                $purchaseItemModel->insertBatch($purchaseItems);
            }
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            $res = array_merge($res, ['message' => $e->getMessage()]);
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
     * return jwon for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        $model = new PurchaseModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Purchase deleted successfully!",
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
