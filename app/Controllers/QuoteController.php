<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QuoteModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class QuoteController extends BaseController
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
