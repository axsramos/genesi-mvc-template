<?php

namespace App\Core;

class Config
{
    public static $DIR_BASE = null;
    public static $URL_BASE = null;
    public static $HOMEPAGE = null;
    public static $HOMEPAGE_METHOD = null;
    public static $PAGE_NOT_FOUND = null;
    public static $PAGE_NOT_FOUND_METHOD = null;
    public static $ISPRODUCTION = null;
    public static $DIR_FILE_LOG = null;
    public static $APP_NAME = null;
    public static $APP_KEY = null;
    public static $APP_TOKEN = null;
    public static $APP_DEBUG = null;
    public static $APP_ENV = null;
    public static $APP_URL = null;
    public static $MAIL_SERVICE = null;
    public static $MAIL = null;
    public static $STATIC_AUTHETICATION = null;
    public static $AUTHENTICATION_SESSION_LIMIT = null;
    public static $DB_STORAGE = null;
    public static $MONITORING_QUERY = null;
    private static $instance = null;
    private static $envs = null;

    private function __construct()
    {
        // Define o caminho raiz do projeto (um nível acima de /App/Core)
        $projectRoot = dirname(__DIR__, 2);
        self::$DIR_BASE = $projectRoot;

        // Define a URL base de forma relativa.
        // Se o index.php estiver em /public, a base será vazia ('').
        // Se estiver na raiz, a base será '/public' para que os assets sejam encontrados.
        $publicDir = $projectRoot . DIRECTORY_SEPARATOR . 'public';
        self::$URL_BASE = (str_starts_with($_SERVER['SCRIPT_FILENAME'], $publicDir)) ? '' : '/public';

        self::$HOMEPAGE = 'Home';
        self::$HOMEPAGE_METHOD = 'index';
        self::$PAGE_NOT_FOUND = 'PageNotFound';
        self::$PAGE_NOT_FOUND_METHOD = 'pageNotFound';

        $envs = EnvironmentVars::setEnvironmentVariables();

        self::readConfig();

        foreach ($envs as $keyDefault => $valueDefault) {
            $applyDefault = true;
            foreach (self::$envs as $key => $value) {
                if ($key == $keyDefault) {
                    $applyDefault = false;
                    break;
                }
            }
            if ($applyDefault) {
                self::$envs[$keyDefault] = $valueDefault;
            }
        }

        /**
         * Define locale and timezone
         */
        setlocale(LC_ALL, self::$envs['APP_LOCALE']);
        date_default_timezone_set(self::$envs['APP_TIMEZONE']);

        /**
         * Define APP_NAME and APP_KEY
         * APP_KEY // chave de segurança para o sistema
         * APP_TOKEN // token genérico para obter acesso ao sistema (área publica)
         */
        self::$APP_NAME = self::$envs['APP_NAME'];
        self::$APP_KEY = self::$envs['APP_KEY'];
        self::$APP_TOKEN = self::$envs['APP_TOKEN'];

        /**
         * Define Environment
         */
        self::$APP_ENV = self::$envs['APP_ENV'];
        self::$APP_DEBUG = (self::$envs['APP_DEBUG'] == 'true' ? true : false);
        self::$ISPRODUCTION = (self::$APP_ENV == 'production' ? true : false);
        

        ini_set('display_errors', self::$APP_DEBUG);

        self::$APP_URL = self::$envs['APP_URL'];
        self::$STATIC_AUTHETICATION = (self::$envs['STATIC_AUTHETICATION'] == 'true'? true : false);
        self::$AUTHENTICATION_SESSION_LIMIT = self::$envs['AUTHENTICATION_SESSION_LIMIT'];

        /**
         * Database Default
         */
        self::$DB_STORAGE = array(
            'Default' => array(
                'DB_CONNECTION' => self::$envs['DB_CONNECTION'],
                'DB_HOST' => self::$envs['DB_HOST'],
                'DB_PORT' => self::$envs['DB_PORT'],
                'DB_DATABASE' => self::$envs['DB_DATABASE'],
                'DB_USERNAME' => self::$envs['DB_USERNAME'],
                'DB_PASSWORD' => self::$envs['DB_PASSWORD'],
                'DB_CHARSET' => self::$envs['DB_CHARSET'],
                'DB_COLLATION' => self::$envs['DB_COLLATION'],
                'DB_PREFIX' => self::$envs['DB_PREFIX'],
            ),
            'SAAS' => array(
                'DB_CONNECTION' => self::$envs['DB_SAAS_CONNECTION'],
                'DB_HOST' => self::$envs['DB_SAAS_HOST'],
                'DB_PORT' => self::$envs['DB_SAAS_PORT'],
                'DB_DATABASE' => self::$envs['DB_SAAS_DATABASE'],
                'DB_USERNAME' => self::$envs['DB_SAAS_USERNAME'],
                'DB_PASSWORD' => self::$envs['DB_SAAS_PASSWORD'],
                'DB_CHARSET' => self::$envs['DB_SAAS_CHARSET'],
                'DB_COLLATION' => self::$envs['DB_SAAS_COLLATION'],
                'DB_PREFIX' => self::$envs['DB_SAAS_PREFIX'],
            ),
        );

        /**
         * Define Service Mail
         * [MAIL_MAILER] => log // para logar os emails
         * [MAIL_MAILER] => smtp // para enviar os emails
         */
        self::$MAIL_SERVICE = (self::$envs['MAIL_SERVICE'] == 'true' ? true : false);

        // Mail Default //
        self::$MAIL = array(
            'MAIL_MAILER' => self::$envs['MAIL_MAILER'],
            'MAIL_AUTH' => self::$envs['MAIL_AUTH'],
            'MAIL_HOST' => self::$envs['MAIL_HOST'],
            'MAIL_PORT' => self::$envs['MAIL_PORT'],
            'MAIL_USERNAME' => self::$envs['MAIL_USERNAME'],
            'MAIL_PASSWORD' => self::$envs['MAIL_PASSWORD'],
            'MAIL_ENCRYPTION' => self::$envs['MAIL_ENCRYPTION'],
            'MAIL_FROM_ADDRESS' => self::$envs['MAIL_FROM_ADDRESS'],
            'MAIL_FROM_NAME' => self::$envs['MAIL_FROM_NAME'],
        );

        /**
         * Define Logs
         */
        self::$DIR_FILE_LOG = self::$DIR_BASE . self::$envs['DIR_FILE_LOG'];
    }

    public static function getInstance(): Config
    {
        if (self::$instance === null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public static function getPageGroup()
    {
        return array(
            'auth',
            'manager',
            'support'
        );
    }

    public static function getPathPrivacy(): string
    {
        return self::$DIR_BASE . '/App/Static/Template/privacy.html';
    }

    public static function getPathTerms(): string
    {
        return self::$DIR_BASE . '/App/Static/Template/terms.html';
    }

    public static function getPathMailRegister(): string
    {
        return self::$DIR_BASE . '/App/Static/Template/email_register.html';
    }

    public static function getPathMailRecovery(): string
    {
        return self::$DIR_BASE . '/App/Static/Template/email_recovery.html';
    }

    public static function getPathRules(string $directory): string
    {
        return self::$DIR_BASE . '/App/Static/Rules/' . $directory . '/';
    }

    public static function getPathRepositories(): string
    {
        return self::$DIR_BASE . '/Repositories/';
    }

    public static function getPathLicense(): string
    {
        return self::$DIR_BASE . '/App/Static/License/';
    }

    public static function getPathUserAccounts(): string
    {
        return self::$DIR_BASE . '/App/Static/Auth/UserAccounts.json';
    }

    public static function getRulesPassword(): array
    {
        return array(
            'MinimumLength' => 6,
            'RequireSimbols' => false,
            'RequireNumbers' => false,
            'RequireUppercase' => false,
            'RequireLowercase' => false,
        );
    }

    public static function getTypeUsersAdminPortal(): array
    {
        // return type user //
        return array('ADMINISTRATOR PORTAL');
    }

    public static function getTypeUsersSupportPortal(): array
    {
        // return type user //
        return array('SUPPORT ACCOUNT');
    }

    public static function getTypeUsersAdminLocal(): array
    {
        // return type user //
        return array('ADMIN ACCOUNT');
    }

    public static function getTypeUsersRestrictAccess(): array
    {
        // return type user //
        return array_merge(self::getTypeUsersAdminPortal(), self::getTypeUsersSupportPortal(), self::getTypeUsersAdminLocal());
    }

    public static function writeConfig(string $dataContent)
    {
        $path = self::$DIR_BASE . '/.env-new';

        file_put_contents($path, $dataContent);
    }
    
    public static function  getEnvs()
    {
        return self::$envs;
    }

    private static function readConfig()
    {
        $path = self::$DIR_BASE . '/.env';
        $envs = [];

        if (file_exists($path)) {
            $file = file_get_contents($path, true);

            $line = '';
            $lines = [];
            for ($i = 0; $i < strlen($file); $i++) {
                $line .= $file[$i];
                if (($file[$i] == chr(10)) || ($file[$i] == chr(13))) {
                    if (! empty($line)) {
                        $lines[] = $line;
                    }
                    $line = '';
                }
            }

            foreach ($lines as $line) {
                if (str_contains($line, '=')) {
                    $parts = explode('=', $line);
                    if (count($parts) >= 2 && (substr($parts[0], 0, 1) != '#')) {
                        $key = '';
                        $value = '';
                        for ($i = 0; $i < count($parts); $i++) {
                            if ($i == 0) {
                                $key = trim($parts[$i]);
                                $value = '';
                                continue;
                            }
                            if ($i == 1) {
                                if (str_contains($parts[$i], '#')) {
                                    $value .= trim(substr($parts[$i], 0, strpos($parts[$i], '#')));
                                } else {
                                    $value .= trim($parts[$i]);
                                }
                            } else {
                                // repor valor removido na função explode //
                                if (str_contains($parts[$i], '#')) {
                                    $value .= '=' . trim(substr($parts[$i], 0, strpos($parts[$i], '#')));
                                } else {
                                    $value .= '=' . trim($parts[$i]);
                                }
                            }
                        }
                        $envs[$key] = $value;
                    }
                }
            }
        }

        self::$envs = $envs;
    }

    public static function getContact(string $type): string
    {
        $contact = '';

        switch ($type) {
            case 'support':
                if (isset(self::$envs['CONTACT_SUPPORT'])) {
                    $contact = self::$envs['CONTACT_SUPPORT'];
                }
                break;
            case 'sales':
                if (isset(self::$envs['CONTACT_SALES'])) {
                    $contact = self::$envs['CONTACT_SALES'];
                }
                break;
            case 'general':
                if (isset(self::$envs['CONTACT_GENERAL'])) {
                    $contact = self::$envs['CONTACT_GENERAL'];
                }
                break;
            default:
                if (isset(self::$envs['CONTACT_EMAIL'])) {
                    $contact = self::$envs['CONTACT_EMAIL'];
                }
                break;
        }

        if (empty($contact)) {
            $contact = 'contato@localhost.com';
        }

        return $contact;
    }
}
