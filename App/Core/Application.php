<?php

namespace App\Core;

class Application
{
    protected $controller;
    protected $method;
    protected $isPageNotFound;
    protected $parameters;
    private $idxUrl;
    private $version;
    private $groups;
    private $group;

    public function __construct()
    {
        $this->controller = Config::$HOMEPAGE;
        $this->method = Config::$HOMEPAGE_METHOD;
        $this->isPageNotFound = false;
        $this->parameters = array();
        $this->idxUrl = 0;
        $this->version = '';
        $this->groups = Config::getPageGroup();
        $this->group = "\\";

        AuthSession::create();

        $this->runApplication();
    }

    private function runApplication(): void
    {
        $urlParameters = $this->parseUrl();

        $this->getVersion($urlParameters);
        $this->getGroup($urlParameters);
        $this->getControllerFromUrl($urlParameters);
        $this->getMethodFromUrl($urlParameters);
        $this->getParameterFromUrl($urlParameters);

        /**
         * DEBUG
         */
        // echo '<br><br>url:<br>';
        // print_r($urlParameters);
        // echo '<br><br>version:<br>';
        // print_r($this->version);
        // echo '<br><br>group:<br>';
        // print_r($this->group);
        // echo '<br><br>controller:<br>';
        // print_r($this->controller);
        // echo '<br><br>method:<br>';
        // print_r($this->method);
        // echo '<br><br>parameters:<br>';
        // print_r($this->parameters);
        // echo '<br><br>';
        // die();

        call_user_func_array([$this->controller, $this->method], $this->parameters);
    }

    private function parseUrl(): array
    {
        $requestURI = explode('/', substr($_SERVER['REQUEST_URI'], 1));
        return $requestURI;
    }

    private function getVersion(array $url): void
    {
        // Confere se o primeiro caractere em $url[1] é 'V' e os seguintes são dígitos. //
        if (isset($url[1])) {
            if (preg_match('/^V\d+$/', $url[1])) {
                $this->version = "\\"  . trim(strtoupper($url[1]));
                $this->idxUrl = 2;
            }
        }
    }

    private function getGroup(array $url): void
    {
        if (isset($url[$this->idxUrl])) {
            if (in_array(strtolower($url[$this->idxUrl]), $this->groups)) {
                $this->group = "\\"  . trim(ucfirst($url[$this->idxUrl])) . "\\";
                $this->idxUrl += 1;
            }
        }
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

        if (empty($this->version)) {
            $className = "App\\Controllers" . $this->group . $pageName;
        } else {
            $className = "App\\Controllers\\" . $url[0] . $this->version . $this->group . $pageName;
        }

        $fileResource = str_replace("\\", "/", $className) . ".php";
        $fileResource = Config::$DIR_BASE . '/' . $fileResource;

        if (! file_exists($fileResource)) {
            $this->isPageNotFound = true;

            $fileResource = Config::$DIR_BASE . "/App/Controllers/" . Config::$PAGE_NOT_FOUND . ".php";
            $className = "App\\Controllers\\" . Config::$PAGE_NOT_FOUND . "";
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
