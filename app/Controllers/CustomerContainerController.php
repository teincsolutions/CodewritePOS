<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerContainerModel;
use CodeIgniter\Database\RawSql;
use CodeIgniter\HTTP\Response;

class CustomerContainerController extends BaseController
{
    public function index()
    {
        //
    }

     /**
     * return json for datatables
     * @return Response - http response
     */
    public function report_datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new CustomerContainerModel();
        $builder = $model->builder();
        $builder->select('customer_containers.store_id,container_id,sale_id,unit_price, SUM(ifnull(qty_in,0))as qty_in,SUM(ifnull(qty_out,0))as qty_out, SUM((ifnull(qty_out,0)-ifnull(qty_in,0))) as qty_bal, SUM((ifnull(qty_out,0)-ifnull(qty_in,0))*unit_price) as total_bal',false);
        $builder->join('sales', 'sales.id=customer_containers.sale_id');
        $builder->groupBy('container_id');

        return $this->response->setJSON(toBuilderDatatableResult($builder, $inputs, function($item){
            $item->store =   $item->store = model('StoreModel')->where('id', $item->store_id)->first();
            $item->container = model('ContainerModel')->where('id', $item->container_id)->first();
            return $item;
        }));
    }
}
