<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QuoteModel;
use CodeIgniter\HTTP\Response;

class QuoteController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $data = [
            'title' => 'Quote List',
        ];
        return view('pages/quotes/list_quote', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit($id = null)
    {
        $data = [
            'title' => 'Create Quote'
        ];

        if ($id) {
            $model = new QuoteModel();
            $data = array_merge($data, [
                'quote' => $model->find($id),
                'title' => 'Edit Quote',
            ]);
        }
        return view('pages/quotes/edit_quote', $data);
    }

    /**
     * return view for show
     * @return Response - http response
     */
    public function show($id)
    {
        $data = [
            'title' => 'Quote Details'
        ];
        $model = new QuoteModel();
        $data = array_merge($data, [
            'quote' => $model->find($id),
        ]);

        return view('pages/quotes/show_quote', $data);
    }

    /**
     * return json for datatables
     * @return Response - http response
     */
    public function datatable(): Response
    {
        $inputs = $this->request->getVar();
        $model = new QuoteModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }
}
