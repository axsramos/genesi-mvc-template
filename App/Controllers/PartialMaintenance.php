<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Config;
use App\Class\Pattern\FormDesign;

class PartialMaintenance extends Controller
{
    private $formDesign;

    public function __construct()
    {
        $this->formDesign = FormDesign::withSideBarAdmin('MANUTENÇÃO PROGRAMADA', 'Sistema em Manutenção', 'Recursos limitados', $this->getUserMenu(), $this->getSideMenu());
    }

    public function index()
    {
        $data = [
            "FormDesign" => $this->formDesign,
        ];

        if (!Config::$STATIC_AUTHETICATION) {
            header('Location: /Home');
        }

        $this->view('SBAdmin/PartialMaintenanceView', $data);
    }
}
