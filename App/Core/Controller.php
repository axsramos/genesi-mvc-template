<?php

namespace App\Core;

class Controller
{
    public $message;

    public function model(string $model): object
    {
        require 'App/Models/' . $model . '.php';

        $classe = 'App\\Models\\' . $model;

        return new $classe();
    }

    public function view(string $view, array $data = []): void
    {
        require 'App/Views/' . $view . '.php';
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

    // todo: validate access //
    // for next version //
    // protected function validateAccess(string $prg_id = '', bool $static_page = false): void {}
}
