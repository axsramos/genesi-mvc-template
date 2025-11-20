<?php

namespace App\Controller\Manager;

use App\Core\Controller;

class Home extends Controller
{
    public function index(): void
    {
        $this->view('SBAdmin/Manager/HomeView');
    }
}
