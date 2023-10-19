<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QuoteItemModel;
use App\Models\QuoteModel;
use App\Models\StoreModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Psr\Log\LoggerInterface;

class QuoteController extends BaseController
{
    /**
     * return view for list
     * @return Response - http response
     */
    public function index()
    {
        $storeModel = new StoreModel();
        
        $data = [
            'title' => 'Quote List',
            'stores' => $storeModel->where('status','opened')->findAll(),
        ];
        return view('pages/quotes/list_quote', $data);
    }

    /**
     * return view for edit
     * @return Response - http response
     */
    public function edit()
    {
        $model = new QuoteModel();
        $lastItem = $model->orderBy('id', 'desc')->first();
        $lastId = $lastItem ? $lastItem->id : 1;
        $storeModel = new StoreModel();

        $quoteWhere = ['quote_date' => date('Y-m-d', time()), 'user_id' => (auth()->user()->id ?? 0)];
        $data = [
            'title' => 'Create a Quote',
            'invoice' => substr(time() + $lastId, 0, 10),
            'stores' => $storeModel->where('status','opened')->findAll(),
            'quoteList' => $model->where($quoteWhere)->findAll(),
        ];

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

        return view('pages/quotes/invoice', $data);
    }

    /**
     * return json for save
     * @return Response - http response
     */
    public function save()
    {
        if (!auth()->user()->can('quotes.create'))
        return $this->response->setJSON([
            'status' => false,
            'message' => "Don't have permission to create this record!"
        ]);

        $model = new QuoteModel();
        $quoteItemModel = new QuoteItemModel();

        $inputs = $this->request->getVar();
        if (auth()->user())
            $inputs['user_id'] = (auth()->user()->id ?? 0);

        unset($inputs['items']);

        $inputs['quote_date'] = date('Y-m-d', strtotime($inputs['quote_date']));

        $items = $this->request->getVar('items');
        if (!$items) return $this->response->setJSON(
            [
                'status' => false,
                'data' => null,
                'message' => "No product selected!",
                'input' => $inputs,
            ]
        );
        $id = $this->request->getPost('id');

        $res = [
            'status' => false,
            'data' => null,
            'message' => "Quote couldn't be save!",
            'input' => $inputs,
        ];
        $quote = $model->where('id', $id)->first();
        $this->db = Database::connect();

        if ($quote) $res = array_merge($res, ['message' => "Quote updated successfully!"]);
        else $res = array_merge($res, ['message' => "Quote created successfully!"]);

        try {
            $this->db->transException(true)->transStart();
            $saved = $model->save($inputs, true);

            if ($saved && !$quote) {
                $id = $model->getInsertID();
                $quoteItems = [];
                foreach ($items as $k => $row) {
                    $items[$k]['quote_id'] = $id;
                    if (empty($items[$k]['tax_id'])) $items[$k]['tax_id'] = null;
                    if (is_null($items[$k]['store_id']) || empty($items[$k]['store_id'])) $items[$k]['store_id'] = $inputs['store_id'];

                    array_push($quoteItems, $items[$k]);
                }
                $quoteItemModel->insertBatch($quoteItems);
            }
            $this->db->transComplete();
        } catch (DatabaseException $e) {
            $res = array_merge($res, [
                'message' => $e->getMessage(),
            ]);
            return $this->response->setJSON($res);
        }
        if ($this->db->transStatus()) {
            $quote = $model->find($id);
            $res = array_merge($res, [
                'status' => true,
                'data' => $quote,
                'receipt' => view('pages/quotes/pos_receipt', ['quote' => $quote])
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
        $model = new QuoteModel();
        return $this->response->setJSON(toDatatableResult($model, $inputs));
    }

    /**
     * return json for delete
     * @return Response - http response
     */
    public function delete($id = null)
    {
        if (!auth()->user()->can('quotes.delete'))
            return $this->response->setJSON([
                'status' => false,
                'message' => "Don't have permission to delete this record!"
            ]);

        $model = new QuoteModel();
        if ($model->delete($id)) {
            $res = [
                'status' => true,
                'message' => "Quote deleted successfully!",
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
