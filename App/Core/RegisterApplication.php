<?php

namespace App\Core;

use App\Helpers\DataContentInFiles;

class RegisterApplication
{
    public static function getData(): string
    {
        $path = self::getPath();

        if (!DataContentInFiles::checkFileExists($path)) {
            self::generateFileRegister($path, '');
        }

        return DataContentInFiles::getDataContent($path);
    }

    public static function generateFileRegister(string $path, string $dataContent): void
    {
        if (empty($dataContent)) {
            $data = self::getFileRegisterDefault();
            $dataContent = json_encode($data, true);
        }

        DataContentInFiles::setDataContent($path, $dataContent);
    }

    public static function getPath(): string
    {
        $path = Config::getPathLicense();
        $path = $path . 'Register.json';

        return $path;
    }

    private static function getFileRegisterDefault(): array
    {
        $data = array();

        // Variaveis de Ambiente //
        $data['Register']['VA']['CasAppEnv'] = Config::$APP_ENV;
        $data['Register']['VA']['CasAppName'] = Config::$APP_NAME;
        $data['Register']['VA']['CasAppUrl'] = Config::$APP_URL;
        $data['Register']['VA']['CasAppKey'] = GenerateKey::generateKey();
        $data['Register']['VA']['CasAppToken'] = GenerateKey::generateKey();

        // Conta de Administrador //
        $data['Register']['CA']['CasAppAdmFirstName'] = 'Admin';
        $data['Register']['CA']['CasAppAdmLastName'] = 'Account';
        $data['Register']['CA']['CasAppAdmAccount'] = 'manager-admin@uorak.com';
        $data['Register']['CA']['CasAppAdmPassword'] = '';
        $data['Register']['CA']['CasAppAdmProfile'] = 'ADMINISTRATOR PORTAL';

        // Conta de Suporte //
        $data['Register']['CS']['CasAppSupFirstName'] = 'Support';
        $data['Register']['CS']['CasAppSupLastName'] = 'Account';
        $data['Register']['CS']['CasAppSupAccount'] = 'manager-support@uorak.com';
        $data['Register']['CS']['CasAppSupPassword'] = '';
        $data['Register']['CS']['CasAppSupProfile'] = 'SUPPORT ACCOUNT';

        return $data;
    }
}
