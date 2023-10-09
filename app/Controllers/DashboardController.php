<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class DashboardController extends BaseController
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

    public function index(): string
    {
        return view('dashboard');
    }
    
    public function addGroup() : string {
        $user = auth()->user();
       // $user->addGroup('developer');
       // $user->addPermission('sales.create', 'sales.edit', 'sales.view');
      // $user->removeGroup('developer');
       return print_r($user->can('sales.delete')?'true':'false');
    }
}
