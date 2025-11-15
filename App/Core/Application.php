<?php

namespace App\Core;

class Application
{
    protected $controller;
    protected $method;
    protected $isPageNotFound;
    protected $parameters;
    private $idxUrl;

    public function __construct()
    {
        $this->controller = Config::$HOMEPAGE;
        $this->method = Config::$HOMEPAGE_METHOD;
        $this->isPageNotFound = false;
        $this->parameters = array();
        $this->idxUrl = 0;

        // todo: create session //
        // for next version //

        $this->runApplication();
    }

    private function runApplication(): void
    {
        $urlParameters = $this->parseUrl();

        $this->getControllerFromUrl($urlParameters);
        $this->getMethodFromUrl($urlParameters);
        $this->getParameterFromUrl($urlParameters);

        call_user_func_array([$this->controller, $this->method], $this->parameters);
    }

    private function parseUrl(): array
    {
        $requestURI = explode('/', substr($_SERVER['REQUEST_URI'], 1));
        return $requestURI;
    }

    private function getControllerFromUrl(array $url): void
    {
        /**
         * Por padrão rota é home
         * Quando rota for "/", assume home
         * Aceita rota CamelCase ou separador hífen. Ex: [HomePage | home-page | Home-Page]
         * O nome da rota é convertido para CamelCase e o controller sempre Camelcase. Ex: HomePage
         */
        if (isset($url[$this->idxUrl])) {
            $pageName = ucwords(str_replace('-', ' ', ucfirst($url[$this->idxUrl])));
            $pageName = str_replace(' ', '', $pageName);
        }

        if (empty($pageName)) {
            $pageName = ucfirst(Config::$HOMEPAGE);
        }

        $className = "App\\Controllers\\" . $pageName;

        $fileResource = str_replace("\\", "/", $className) . ".php";

        if (! file_exists($fileResource)) {
            $this->isPageNotFound = true;

            $fileResource = "App/Controllers/" . Config::$PAGE_NOT_FOUND . ".php";
            $className = "App\\Controllers\\" . Config::$PAGE_NOT_FOUND;
        }

        require_once $fileResource;

        $this->controller = new $className();
    }

    private function getMethodFromUrl(array $url): void
    {
        $this->idxUrl += 1;

        if ($this->isPageNotFound) {
            $this->method = Config::$PAGE_NOT_FOUND_METHOD;
        } else {
            if (isset($url[$this->idxUrl]) && !empty($url[$this->idxUrl])) {
                if (method_exists($this->controller, $url[$this->idxUrl])) {
                    $this->method = $url[$this->idxUrl];
                } else {
                    $this->method = Config::$PAGE_NOT_FOUND_METHOD;
                }
            }
        }
    }

    private function getParameterFromUrl(array $url): void
    {
        $this->idxUrl += 1;

        if (count($url) > $this->idxUrl) {
            $this->parameters = array_slice($url, $this->idxUrl);
        }
    }
}