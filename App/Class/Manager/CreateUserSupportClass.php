<?php

namespace App\Class\Manager;

use App\Core\Config;
use App\Models\CAS\CasUsrModel;
use App\Models\CAS\CasRpsModel;
use App\Models\CAS\CasTusModel;
use App\Models\CAS\CasRpuModel;

class CreateUserSupportClass
{
    public function run(string $rps_id): void
    {
        $tus_id = $this->createDefaultTypeUser($rps_id);
        if ($tus_id === false || $tus_id === '') {
            return; // error //
        }

        $path = Config::getPathRules('Manager');
        $path .= 'SupportUser.json';

        if (file_exists($path)) {
            $json = file_get_contents($path);
            $data = json_decode($json, true);
            
            foreach ($data as $key => $value) {
                $obCasUsrModel = new CasUsrModel();
                $obCasUsrModel->setSelectedFields(['CasUsrCod']);
                
                // search for user with (CasUsrCod) //
                if (isset($value['CasUsrCod']) && !empty($value['CasUsrCod'])) {
                    $obCasUsrModel->setSelectedFields(['CasUsrCod']);
                    $obCasUsrModel->CasUsrCod = $value['CasUsrCod'];
                    $obCasUsrModel->CasUsrDsc = $value['CasUsrDsc'];
                    if ($obCasUsrModel->readRegister()) {
                        $this->associateUser($rps_id, $tus_id, $obCasUsrModel->CasUsrCod, $obCasUsrModel->CasUsrDsc);
                        continue; // user already exists //
                    }
                }

                // search for user with (mail account) //
                if (isset($value['CasUsrDmn']) && isset($value['CasUsrLgn'])) {
                    $obCasUsrModel->CasUsrDmn = $value['CasUsrDmn'];
                    $obCasUsrModel->CasUsrLgn = $value['CasUsrLgn'];
                    $obCasUsrModel->CasUsrDsc = $value['CasUsrDsc'];
                    if ($obCasUsrModel->existsMailAccount()) {
                        $this->associateUser($rps_id, $tus_id, $obCasUsrModel->CasUsrCod, $obCasUsrModel->CasUsrDsc);
                        continue; // user already exists //
                    }
                    // create user and associate with repository//
                    $this->createAccount($rps_id, $tus_id, $obCasUsrModel, $value);
                }
            }
        }
    }

    private function createAccount(string $rps_id, string $tus_id, object $obCasUsrModel, object $value): void
    {
        if (empty($value['CasUsrDmn']) || empty($value['CasUsrLgn'])) {
            return; // invalid data //
        }
        if (isset($value['CasUsrNme'])) {
            if (empty($value['CasUsrNme'])) {
                $value['CasUsrNme'] = 'Support';
            }
            $obCasUsrModel->CasUsrNme = $value['CasUsrNme'];
        }
        if (isset($value['CasUsrSnm'])) {
            if (empty($value['CasUsrSnm'])) {
                $value['CasUsrSnm'] = 'Account';
            }
            $obCasUsrModel->CasUsrSnm = $value['CasUsrSnm'];
        }
        if (isset($value['CasUsrNck'])) {
            $obCasUsrModel->CasUsrNck = $value['CasUsrNck'];
        }
        if (isset($value['CasUsrDsc'])) {
            if (empty($value['CasUsrDsc'])) {
                $value['CasUsrDsc'] = 'Support Account';
            }
            $obCasUsrModel->CasUsrDsc = $value['CasUsrDsc'];
        }
        if (isset($value['CasUsrPwd'])) {
            if (empty($value['CasUsrPwd'])) {
                $value['CasUsrPwd'] = md5(strtolower($value['CasUsrLgn'] . $value['CasUsrDmn']));
            }
            $obCasUsrModel->CasUsrPwd = md5($value['CasUsrPwd']);
        }
        if (isset($value['CasUsrBlq'])) {
            if (empty($value['CasUsrBlq'])) {
                $value['CasUsrBlq'] = 'N';
            }
            if (substr(strtoupper($value['CasUsrBlq']), 0, 1) == 'S') {
                $value['CasUsrBlq'] = 'S';
            } else {
                $value['CasUsrBlq'] = 'N';
            }
            $obCasUsrModel->CasUsrBlq = $value['CasUsrBlq'];
        }
        
        if ($obCasUsrModel->createRegister()) {
            $this->associateUser($rps_id, $tus_id, $obCasUsrModel->CasUsrCod, $obCasUsrModel->CasUsrDsc);
        }
    }

    private function associateUser(string $rps_id, string $tus_id, string $usr_id, string $usr_dsc): void
    {
        $obCasRpuModel = new CasRpuModel();
        $obCasRpuModel->CasRpsCod = $rps_id;
        $obCasRpuModel->CasUsrCod = $usr_id;
        $obCasRpuModel->CasTusCod = $tus_id;
        $obCasRpuModel->CasRpuDsc = $usr_dsc;
        $result_rpu = $obCasRpuModel->createRegister();
    }

    private function createDefaultTypeUser(string $rps_id): string | bool
    {
        // check repository //
        $obCasRpsModel = new CasRpsModel();
        $obCasRpsModel->setSelectedFields(['CasRpsCod']);

        $obCasRpsModel->CasRpsCod = $rps_id;
        if (! $obCasRpsModel->readRegister()) {
            return false; // error //
        }

        // check type user //
        $tus_id = 'U1VQUE9SVCBBQ0NPVU5U'; // SUPPORT ACCOUNT in base64
        
        $obCasTusModel = new CasTusModel();
        $obCasTusModel->setSelectedFields(['CasRpsCod','CasTusCod', 'CasTusDsc']);
        $obCasTusModel->CasRpsCod = $rps_id;
        $obCasTusModel->CasTusCod = $tus_id;
        if (! $obCasTusModel->readRegister()) {
            $obCasTusModel->setSelectedFields(CasTusModel::FIELDS);
            $obCasTusModel->CasTusDsc = 'SUPPORT ACCOUNT';
            $obCasTusModel->CasTusBlq = 'N';
            $obCasTusModel->CasTusGrp = 'SUPPORT PORTAL MANAGER';
            if (!$obCasTusModel->createRegister()) {
                return false; // error //
            }
        }

        return $tus_id;
    }
}
