<?php

namespace App\Class\Manager;

use App\Models\CAS\CasUsrModel;
use App\Models\CAS\CasRpsModel;
use App\Models\CAS\CasRpuModel;
use App\Models\CAS\CasTusModel;
use App\Class\Manager\CreateUserSupportClass;

class CreateUserRepositoryAccount
{
    public function run(array $dataAccount): void
    {
        // step 1: create user account //
        $this->createUser($dataAccount);

        // step 2: create repository account //
        $this->createRepository($dataAccount);

        // step 3: create support account //
        $obCreateUserSupportClass = new CreateUserSupportClass();
        $obCreateUserSupportClass->run($dataAccount['Repository']);

        // step 4: create type user //
        $obCasTusModel = new CasTusModel();
        $obCasTusModel->setSelectedFields(['CasRpsCod','CasTusCod', 'CasTusDsc']);
        $obCasTusModel->CasRpsCod = $dataAccount['Repository'];
        $obCasTusModel->CasTusCod = 'VVNFUiBBQ0NPVU5U'; // USER ACCOUNT in base64
        $obCasTusModel->CasTusDsc = 'USER ACCOUNT';
        $obCasTusModel->CasTusBlq = 'N';
        $obCasTusModel->CasTusLnk = '/Home';
        $obCasTusModel->CasTusGrp = 'USER';
        if (!$obCasTusModel->readRegister()) {
            $result = $obCasTusModel->createRegister();
        }

        $obCasTusModel = new CasTusModel();
        $obCasTusModel->setSelectedFields(['CasRpsCod','CasTusCod', 'CasTusDsc']);
        $obCasTusModel->CasRpsCod = $dataAccount['Repository'];
        $obCasTusModel->CasTusCod = 'QURNSU4gQUNDT1VOVA=='; // ADMIN ACCOUNT in base64
        $obCasTusModel->CasTusDsc = 'ADMIN ACCOUNT';
        $obCasTusModel->CasTusBlq = 'N';
        $obCasTusModel->CasTusLnk = '/Home';
        $obCasTusModel->CasTusGrp = 'ADMIN';
        if (!$obCasTusModel->readRegister()) {
            $result = $obCasTusModel->createRegister();
        }

        // step 5: associete user account with repository //
        $obCasRpuModel = new CasRpuModel();
        $obCasRpuModel->setSelectedFields(['CasRpsCod', 'CasUsrCod', 'CasTusCod', 'CasRpuDsc', 'CasRpuBlq']);
        $obCasRpuModel->CasRpsCod = $dataAccount['Repository'];
        $obCasRpuModel->CasUsrCod = $dataAccount['USR_ID'];
        if (!$obCasRpuModel->readRegister()) {
            $obCasRpuModel->CasTusCod = $obCasTusModel->CasTusCod;
            $obCasRpuModel->CasRpuDsc = $dataAccount['Account'];
            $obCasRpuModel->CasRpuBlq = 'N';
            $result = $obCasRpuModel->createRegister();
        }
    }

    private function createUser(array $dataAccount): void
    {
        list($vCasUsrLgn, $vCasUsrDmn) = explode('@', $dataAccount['Account']);

        $obCasUsrModel = new CasUsrModel();
        $obCasUsrModel->setSelectedFields(['CasUsrCod', 'CasUsrDmn', 'CasUsrLgn']);
        $obCasUsrModel->CasUsrDmn = '@' . $vCasUsrDmn;
        $obCasUsrModel->CasUsrLgn = $vCasUsrLgn;
        if (!$obCasUsrModel->existsMailAccount()) {
            $obCasUsrModel->setSelectedFields(['CasUsrCod', 'CasUsrDmn', 'CasUsrLgn', 'CasUsrNme', 'CasUsrSnm', 'CasUsrDsc', 'CasUsrPwd', 'CasUsrBlq']);
            $obCasUsrModel->CasUsrCod = $dataAccount['USR_ID'];
            $obCasUsrModel->CasUsrNme = $dataAccount['FirstName'];
            $obCasUsrModel->CasUsrSnm = $dataAccount['LastName'];
            $obCasUsrModel->CasUsrDsc = $dataAccount['USR_LOGGED'];
            $obCasUsrModel->CasUsrPwd = $dataAccount['Password'];
            $obCasUsrModel->CasUsrBlq = 'N';
            $result = $obCasUsrModel->createRegister();
        }
    }

    private function createRepository(array $dataAccount): void
    {
        $obCasRpsModel = new CasRpsModel();

        $obCasRpsModel->setSelectedFields(['CasRpsCod', 'CasRpsDsc', 'CasRpsBlq', 'CasRpsGrp']);
        $obCasRpsModel->CasRpsCod = $dataAccount['Repository'];
        $obCasRpsModel->CasRpsDsc = $dataAccount['Account'];
        $obCasRpsModel->CasRpsBlq = 'N';
        $obCasRpsModel->CasRpsGrp = $dataAccount['Repository'];
        if (!$obCasRpsModel->readRegister()) {
            $result = $obCasRpsModel->createRegister();
        }
    }
}
