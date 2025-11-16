<?php

namespace App\Core;

use App\Helpers\RequiredFields;
use App\Helpers\ObtainDataInput;
use App\Controls\Menu\TopMenuControls;
use App\Controls\Menu\SideMenuControls;
use App\Class\Auth\LoginClass;

class Controller
{
    public $message;

    public function model(string $model): object
    {
        require Config::$DIR_BASE . '/App/Models/' . $model . '.php';

        $classe = 'App\\Models\\' . $model;

        return new $classe();
    }

    public function view(string $view, array $data = []): void
    {
        require Config::$DIR_BASE . '/App/Views/' . $view . '.php';
    }

    public function pageNotFound(bool $api = false): void
    {
        if ($api) {
            http_response_code(404);
            $this->view('jsonView');
        } else {
            $this->view('ErrorView');
        }
    }

    protected function getUserMenu(): string
    {
        return TopMenuControls::getUserMenu();
    }

    protected function getSideMenu(): string
    {
        return SideMenuControls::getSideMenu();
    }

    protected function validateAccess(string $prg_id = ''): void
    {
        // is anonymous or empty user then redirect to login //
        $redirect_login = true;

        if (AuthSession::get()['USR_LOGGED'] !== 'anonymous') {
            $obLoginClass = new LoginClass();
            $dataPermissions = $obLoginClass->permissions($prg_id);
            
            if (in_array('AUTHORIZED', $dataPermissions)) {
                $redirect_login = false;
            }
        }

        if ($redirect_login) {
            header('Location: /Home/Denied');
        }
    }

    protected function getDataInput(array $fields = []): array
    {
        return ObtainDataInput::run($fields);
    }

    protected function checkRequiredFields(array $requiredFields, array $dataEntry): bool
    {
        return RequiredFields::run($requiredFields, $dataEntry);
    }
}
