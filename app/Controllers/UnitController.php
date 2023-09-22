<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnitModel;
use CodeIgniter\HTTP\Response;

class UnitController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Unit List',
        ];
        return view('pages/units/list_unit', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Unit'
        ];

        if ($id) {
            $model = new UnitModel();
            $data = array_merge($data, [
                'unit' => $model->find($id),
                'title' => 'Edit Unit',
            ]);
        }
        return view('pages/units/edit_unit', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new UnitModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
}
