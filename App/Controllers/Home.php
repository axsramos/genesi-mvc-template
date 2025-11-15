<?php

namespace App\Controllers;

use App\Core\Controller;

class Home extends Controller
{
  public function __construct()
  {
    // (validateAccess) Not required for this controller //
    // $this->validateAccess('Home'); //
  }

  public function index()
  {
    $data = array();

    $this->view('SBSimple/HomeView', $data);
  }
}
