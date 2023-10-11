<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnitModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

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
    public function save()
    {
        $model = new UnitModel();
        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = auth()->user()->id;
        $id = $this->request->getPost('id');
        $res = [
            'status' => false,
            'data' => null,
            'message' => null,
            'input' => $inputs,
        ];
        $Unit = $model->where('id', $id)->first();

        if ($Unit) {
            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Unit updated successfully!",
                    'data' => $model->find($id),
                ]);
            } else {
                $res = array_merge($res, [
                    'status' => false,
                    'message' => "Couldn't be updated!"
                ]);
            }
        } else {
            if ($model->save($inputs)) {
                $res = array_merge($res, [
                    'status' => true,
                    'message' => "Unit created successfully!",
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
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new UnitModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

     /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        $model = new UnitModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Unit deleted successfully!",
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
