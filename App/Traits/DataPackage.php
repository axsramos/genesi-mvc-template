<?php

namespace App\Traits;

use App\Core\Config;
use App\Models\CAS\CasAppModel;

trait DataPackage
{
    public function getDataPackage($app_id): array
    {
        $data = array();

        $path = Config::getPathRules('Manager');
        $files = $this->selectedFiles($path);
        $dataReturn = $this->loadFile($app_id, $path, $files);

        if (! empty($dataReturn)) {
            $data = $dataReturn;
        }

        return $data;
    }

    private function selectedFiles(string $directory): array
    {
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));

        $files = array();

        foreach ($scanned_directory as $file) {
            if (is_file($directory . $file)) {
                if ((substr($file, 0, 9) == 'Settings_') && (substr($file, -5) == '.json')) {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    private function loadFile(string $app_id, string $directory, array $files): array | null
    {
        $obCasAppModel = new CasAppModel();
        $obCasAppModel->setSelectedFields(['CasAppCod', 'CasAppVer']);
        $obCasAppModel->CasAppCod = $app_id;

        if ($obCasAppModel->readRegister()) {
            foreach ($files as $file) {
                $json = file_get_contents($directory . DIRECTORY_SEPARATOR . $file);
                $data = json_decode($json, true);

                if ($data['ProductKey'] !== $obCasAppModel->CasAppCod) {
                    continue; // invalid key //
                }
                if ($data['Version'] == $obCasAppModel->CasAppVer) {
                    return $data;
                }
            }
        }

        return null;
    }
}
