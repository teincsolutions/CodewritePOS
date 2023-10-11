<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Authorization\Groups;
use CodeIgniter\Shield\Entities\Group;
use CodeIgniter\Shield\Models\GroupModel;
use Psr\Log\LoggerInterface;

class DashboardController extends BaseController
{

    public function index(): string
    {
        return view('dashboard');
    }
}
