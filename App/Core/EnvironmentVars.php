<?php

namespace App\core;

class EnvironmentVars
{
    public static function setEnvironmentVariables(string $env = 'local'): array
    {
        $envs = array();

        switch ($env) {
            case 'local':
                $envs = self::setEnvLocal();
                break;
            case 'development':
                $envs = self::setEnvDevelopment();
                break;
            case 'staging':
                $envs = self::setEnvStaging();
                break;
            case 'production':
                $envs = self::setProduction();
                break;
            default:
                $envs = self::setEnvLocal();
                break;
        }

        return $envs;
    }

    private static function getEnvDefault(): array
    {
        $env = array(
            // location end region //
            'APP_LOCALE' => 'pt_BR.utf-8',
            'APP_TIMEZONE' => 'America/Sao_Paulo',

            // application //
            'APP_NAME' => '',
            'APP_KEY' => '',
            'APP_TOKEN' => '',

            // environment support //
            'APP_ENV' => '',
            'APP_DEBUG' => false,
            'MONITORING_QUERY' => false,
            'DIR_FILE_LOG' => '/Temp/Logs',

            // environment application //
            'APP_URL' => 'http://localhost',
            'STATIC_AUTHETICATION' => false,
            'AUTHENTICATION_SESSION_LIMIT' => 3600,

            // api //
            'API_SBADMIN_TOKEN'=> 'U0JBRE1JTiAtIEdFTkVSSUMgVE9LRU4=',
            'API_SBADMIN_URL'=> 'http://localhost/auth',

            // database //
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => '127.0.0.1',
            'DB_PORT' => '3306',
            'DB_DATABASE' => 'mysql',
            'DB_USERNAME' => 'root',
            'DB_PASSWORD' => '',
            'DB_CHARSET' => 'utf8mb4',
            'DB_COLLATION' => 'utf8mb4_unicode_ci',
            'DB_PREFIX' => '',

            // mail //
            'MAIL_SERVICE' => false,
            'MAIL_MAILER' => 'log',
            'MAIL_AUTH' => true,
            'MAIL_HOST' => 'smtp.gmail.com',
            'MAIL_PORT' => 587,
            'MAIL_USERNAME' => 'mail.account@gmail.com',
            'MAIL_PASSWORD' => 'password',
            'MAIL_ENCRYPTION' => 'tls',
            'MAIL_FROM_ADDRESS' => 'mail.account@gmail.com',
            'MAIL_FROM_NAME' => 'Mail Account',
        );

        return $env;
    }

    private static function setEnvLocal(): array
    {
        // reset //
        $envs = self::getEnvDefault();

        // application //
        $envs['APP_NAME'] = 'SBAdmin'; // PROJECT SBADMIN LOCAL //
        $envs['APP_KEY'] = 'UFJPSkVDVCBTQkFETUlOIExPQ0FM';
        $envs['APP_TOKEN'] = '51b39438-552e-11f0-afef-fc4596f8a36d';

        // environment support //
        $envs['APP_ENV'] = 'local';
        $envs['APP_DEBUG'] = true;
        $envs['MONITORING_QUERY'] = true;

        // environment application //
        $envs['APP_URL'] = 'http://localhost:8080';
        $envs['STATIC_AUTHETICATION'] = true;
        $envs['AUTHENTICATION_SESSION_LIMIT'] = 86400;

        // database //
        $envs['DB_DATABASE'] = 'local';
        $envs['DB_USERNAME'] = 'root';
        $envs['DB_PASSWORD'] = '';
        $envs['DB_PREFIX'] = '';

        return $envs;
    }

    private static function setEnvDevelopment(): array
    {
        // reset //
        $envs = self::getEnvDefault();

        // application //
        $envs['APP_NAME'] = 'DEV-SBADMIN'; // PROJECT SBADMIN DEVELOPMENT //
        $envs['APP_KEY'] = 'UFJPSkVDVCBTQkFETUlOIERFVkVMT1BNRU5U';
        $envs['APP_TOKEN'] = '28bfa817-55b6-11f0-8e0d-fc4596f8a36d';

        // environment support //
        $envs['APP_ENV'] = 'development';
        $envs['APP_DEBUG'] = true;
        $envs['MONITORING_QUERY'] = true;

        // environment application //
        $envs['APP_URL'] = 'http://localhost:8080';
        $envs['STATIC_AUTHETICATION'] = false;
        $envs['AUTHENTICATION_SESSION_LIMIT'] = 86400;

        // database //
        $envs['DB_DATABASE'] = 'development';
        $envs['DB_USERNAME'] = 'root';
        $envs['DB_PASSWORD'] = '';
        $envs['DB_PREFIX'] = '';

        return $envs;
    }

    private static function setEnvStaging(): array
    {
        // reset //
        $envs = self::getEnvDefault();

        // application //
        $envs['APP_NAME'] = 'QA-SBADMIN'; // PROJECT SBADMIN STAGING //
        $envs['APP_KEY'] = 'UFJPSkVDVCBTQkFETUlOIFNUQUdJTkc=';
        $envs['APP_TOKEN'] = 'c90390d7-55b6-11f0-8e0d-fc4596f8a36d';

        // environment support //
        $envs['APP_ENV'] = 'staging';
        $envs['APP_DEBUG'] = false;
        $envs['MONITORING_QUERY'] = false;

        // environment application //
        $envs['APP_URL'] = 'http://localhost:8080';
        $envs['STATIC_AUTHETICATION'] = false;
        $envs['AUTHENTICATION_SESSION_LIMIT'] = 3600;

        // database //
        $envs['DB_DATABASE'] = 'staging';
        $envs['DB_USERNAME'] = 'root';
        $envs['DB_PASSWORD'] = '';
        $envs['DB_PREFIX'] = '';

        return $envs;
    }

    private static function setProduction(): array
    {
        // reset //
        $envs = self::getEnvDefault();

        // application //
        $envs['APP_NAME'] = 'SBADMIN'; // PROJECT SBADMIN PRODUCTION //
        $envs['APP_KEY'] = 'UFJPSkVDVCBTQkFETUlOIFBST0RVQ1RJT04=';
        $envs['APP_TOKEN'] = '5c4e54d9-55b7-11f0-8e0d-fc4596f8a36d';

        // environment support //
        $envs['APP_ENV'] = 'production';
        $envs['APP_DEBUG'] = false;
        $envs['MONITORING_QUERY'] = false;

        // environment application //
        $envs['APP_URL'] = 'https://localhost';
        $envs['STATIC_AUTHETICATION'] = false;
        $envs['AUTHENTICATION_SESSION_LIMIT'] = 3600;

        // database //
        $envs['DB_DATABASE'] = 'sbadmin';
        $envs['DB_USERNAME'] = 'root';
        $envs['DB_PASSWORD'] = '';
        $envs['DB_PREFIX'] = '';

        return $envs;
    }
}
