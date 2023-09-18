<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BrandModel;
use App\Models\CategoryModel;
use App\Models\ContainerModel;
use App\Models\CustomerContainerModel;
use App\Models\StoreModel;
use App\Models\TaxModel;
use App\Models\UnitModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Response;
use Config\Database;

class ContainerController extends BaseController
{
   /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $storeModel = new StoreModel();
        $data = [
            'title' => 'Container List',
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];
        return view('pages/containers/list_container', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $catModel = new CategoryModel();
        $unitModel = new UnitModel();
        $brandModel = new BrandModel();
        $taxModel = new TaxModel();
        $storeModel  = new StoreModel();

        $data = [
            'title' => 'Create Container',
            'categories' => $catModel->findAll(),
            'units' => $unitModel->findAll(),
            'taxes' => $taxModel->findAll(),
            'brands' => $brandModel->findAll(),
            'stores' => $storeModel->where('status', 'opened')->findAll(),
        ];

        if ($id) {
            $model = new ContainerModel();
            $data = array_merge($data, [
                'container' => $model->find($id),
                'title' => 'Edit Container',
            ]);
        }
        return view('pages/containers/edit_container', $data);
    }

    public function save()
    {
        $model = new ContainerModel();
        $inputs = $this->request->getVar();
        $id = $this->request->getPost('id');
        
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $container = $model->where('id', $id)->first();
        $this->db = Database::connect();
    
        try {
            $this->db->transException(true)->transStart();

            if ($container) {
                if (!auth()->user()->can('containers.edit'))
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => "Don't have permission to edit this record!"
                    ]);

                if ($model->save($inputs)) {
                    $res = array_merge($res, [
                        'status' => true,
                        'message' => "Container updated successfully!",
                        'data' => $model->find($id),
                    ]);
                } else {
                    $res = array_merge($res, [
                        'status' => false,
                        'message' => "Couldn't be updated!"
                    ]);
                }
            } else {
                if (auth()->user())
                    $inputs['user_id'] = auth()->user()->id;

                if (!auth()->user()->can('containers.create'))
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => "Don't have permission to create this record!"
                    ]);

                if ($model->save($inputs)) {
                    $id = $model->getInsertID();
                    $res = array_merge($res, [
                        'status' => true,
                        'message' => "Container created successfully!",
                        'data' => $model->find($id),
                    ]);
                } else {
                    $res = array_merge($res, [
                        'status' => false,
                        'message' => "Couldn't be created!"
                    ]);
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
            return $this->response->setJSON($res);
        }
    }
    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {

        $data = [
            'title' => 'Container Details'
        ];
        $model = new ContainerModel();
        $container = $model->find($id);
        if ($container)
            $data = array_merge($data, [
                'container' => $container,
            ]);

        return view('pages/containers/show_container', $data);
    }

    /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('containers.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new ContainerModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Container deleted successfully!",
            ];
        } else {
            $res = [
                'status' => false,
                'message' => "Couldn't be deleted!"
            ];
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
        $model = new ContainerModel();
        if (setting('App.ContainerDiffForStore') === 'yes') {
            $model->select('containers.id,containers.name,containers.barcode,containers.sku,containers.pdiscount,containers.description,' .
                'containers.category_id,containers.brand_id,containers.unit_id,containers.unit_qty,containers.tax_id,' .
                'containers.user_id,containers.expiration,store_containers.*');

            $model->join('store_containers', 'store_containers.container_id=containers.id');
            $model->where('store_id', $inputs['store_id'] ?? 0);
        } else $model->select('containers.*');

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function customer_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new CustomerContainerModel();
        $model->where('instock !=', 0);
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function expired_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ContainerModel();
        $model->where('expiration <=', date('Y-m-d', time()));
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
    /**
     * return json for search
     * @return Response - http response
     */
    public function search(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ContainerModel();
        if (setting('App.ContainerDiffForStore') === 'yes') {
            $model->select('containers.id,containers.name,containers.barcode,containers.sku,containers.pdiscount,containers.description,' .
                'containers.category_id,containers.brand_id,containers.unit_id,containers.unit_qty,containers.tax_id,' .
                'containers.user_id,containers.expiration,store_containers.*');

            $model->join('store_containers', 'store_containers.container_id=containers.id');
            $model->where('store_id', $inputs['store_id'] ?? 0);
        } else $model->select('containers.*');

        $model->join('categories', 'categories.id=containers.category_id');
        $model->join('brands', 'brands.id=containers.brand_id', 'left');


        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for search
     * @return Response - http response
     */
    public function select2(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ContainerModel();
        $builder = $model->builder();

        $builder->join('units', 'units.id=containers.unit_id');
        $builder->join('brands', 'brands.id=containers.brand_id', 'left');
        if (isset($inputs['exclude']))
            $builder->whereNotIn('containers.id', $inputs['exclude']);
        return $this->response->setJSON(toSelect2BuilderResult(
            $builder,
            ['containers.sku', 'containers.name', 'brands.name'],
            $inputs,
            'concat(ifnull(concat(containers.sku," "),""),containers.name," ",ifnull(brands.name,"")," (",units.label, ")"," ₵",containers.unit_price) as text, containers.*',
        ));
    }
    /**
     * return json for search
     * @return Response - http response
     */
    public function purchase_search(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ContainerModel();
        $model->join('purchase_items', 'purchase_items.container_id=containers.id');
        $model->join('purchase_returns_items', 'purchase_returns_items.purchase_item_id=purchase_items.id', 'left');
        $model->select('containers.id,containers.name,containers.barcode,containers.sku,containers.category_id,containers.brand_id,' .
            'containers.unit_id,containers.discontinued,containers.unit_qty,containers.user_id,containers.expiration,containers.image_uri,' .
            'containers.min_qty,purchase_items.unit_price,purchase_items.tax_id,purchase_items.unit_cost,' .
            '(purchase_items.qty-SUM(ifnull(purchase_returns_items.qty,0))) as max_qty, purchase_items.tax,' .
            'purchase_items.discount,purchase_items.subtotal,purchase_items.store_id,purchase_items.id as purchase_item_id');
        $model->where('purchase_items.purchase_id', $this->request->getGet('purchase_id'));
        $model->groupBy('purchase_items.id');

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for search
     * @return Response - http response
     */
    public function sale_search(): Response
    {
        $inputs = $this->request->getVar();
        $model = new ContainerModel();
        $model->join('sales_items', 'sales_items.container_id=containers.id');
        $model->join('sales_returns_items', 'sales_returns_items.sale_item_id=sales_items.id', 'left');
        $model->select('containers.id,containers.name,containers.barcode,containers.sku,containers.category_id,' .
            'containers.brand_id,containers.unit_id,containers.discontinued,containers.unit_qty,containers.user_id,' .
            'containers.expiration,containers.image_uri,containers.min_qty,sales_items.unit_price,sales_items.unit_cost,' .
            'sales_items.tax_id,sales_items.qty, (sales_items.qty-SUM(ifnull(sales_returns_items.qty,0))) as max_qty, sales_items.tax,' .
            'sales_items.discount,sales_items.subtotal,sales_items.store_id,sales_items.id as sale_item_id');
        $model->where('sales_items.sale_id', $this->request->getGet('sale_id'));
        $model->groupBy('sales_items.id');

        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
}
