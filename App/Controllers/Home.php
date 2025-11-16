<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Class\Pattern\FormDesign;

class Home extends Controller
{
  private $formDesign;

  public function __construct()
  {
    // (validateAccess) Not required for this controller //
    // $this->validateAccess('Home'); //

    $this->formDesign = FormDesign::withSideBarAdmin('PRINCIPAL', 'Home', 'Home', $this->getUserMenu(), $this->getSideMenu());
  }

  public function index(): void
  {
    $data = array(
      "FormDesign" => $this->formDesign,
    );

    // $this->view('SBSimple/HomeView', $data);
    $this->view('SBAdmin/HomeView', $data);
  }

  public function denied(): void
  {
    $deniedFormDesign = FormDesign::withSideBarAdmin(
      'ACESSO NEGADO',      // Título da página
      'Acesso Negado',      // Item principal do Breadcrumb
      '',                   // Subitem do Breadcrumb (opcional)
      $this->getUserMenu(),
      $this->getSideMenu()
    );

    $data = array(
      "FormDesign" => $deniedFormDesign,
    );

    $this->view('SBAdmin/DeniedView', $data);
  }

  public function gc(): void
  {
    session_destroy();
  }
}
