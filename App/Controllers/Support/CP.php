<?php

namespace App\Controllers\Support;

use App\Core\Controller;
use App\Shared\MessageDictionary;
use App\Models\CAS\CasCPModel;
use App\Class\Pattern\FormDesign;
use App\Class\Pattern\FormData;
use App\Class\Pattern\FormatData;
use App\Core\AuthSession;
use App\Models\CAS\CasPrgModel;
use App\Models\CAS\CasMprModel;
use App\Core\RegisterApplication;
use App\Controls\Menu\PanelMenuSupportControls;
use App\Class\Auth\AuthClass;
use App\Core\Config;
use App\Class\Auth\RegisterClass;


class CP extends Controller
{
    private $messages = array();
    private $formDesign;
    // private $obCasCPModel;
    private $dataEntry;

    public function __construct()
    {
        // (validateAccess) Not required for this controller //
        // $this->validateAccess('CONFIGURACAO-PADRAO'); //
        // $this->setProgramParameters('CP', ''); //

        $this->message = new MessageDictionary();

        // $this->obCasCPModel = new CasCPModel();

        $this->formDesign = FormDesign::withSideBarAdmin('PRINCIPAL', 'Configuração Padrão', 'Suporte > Configuração Padrão', $this->getUserMenu(), $this->getSideMenu());
    }

    public function index()
    {
        $this->formDesign['TransMode'] = 'StartPage';

        $this->formDesignOnForm($this->formDesign['TransMode'], 0, 'CPViewWizardStepStart.php', []);

        $dataRegister = json_decode(RegisterApplication::getData(), true);

        if (isset($_POST['btnConfirmStartPage'])) {
            header("Location: /Support/CP/Show/1");
            exit;
        }

        $this->getDataCards('0');

        // /**
        //  * Show Screen
        //  */
        $data = array(
            'FormDesign' => $this->formDesign,
        );

        $this->view('SBAdmin/Support/CPView', $data);
    }

    public function show($id = '')
    {
        $fields = CasCPModel::FIELDS;

        $this->dataEntry = $this->getDataInput($fields);
        $this->dataEntry = FormData::secFields($fields, $this->dataEntry);

        $this->formDesign['TransMode'] = 'NextPage';

        $dataRegister = json_decode(RegisterApplication::getData(), true);

        $isOK = true;

        if (isset($_POST['btnConfirmNextPage'])) {
            if ($id == '0') {
                header("Location: /Support/CP/Show/1");
                exit;
            }

            if ($id == '1') {
                $fieldsValidation = ['CasAppKey', 'CasAppToken', 'CasAppName', 'CasAppEnv', 'CasAppUrl'];
                if ($isOK) {
                    $isOK = $this->checkRequiredField($fieldsValidation);
                }
                if ($isOK) {
                    $isOK = $this->checkLengthField($fieldsValidation);
                }
                if ($isOK) {
                    $isOK = $this->checkKeyField(['CasAppKey', 'CasAppToken']);
                }
                if ($isOK) {
                    $this->saveRegistration($fieldsValidation, 'VA', $dataRegister);

                    header("Location: /Support/CP/Show/2");
                    exit;
                }
            }

            if ($id == '2') {
                $fieldsValidation = ['CasAppAdmFirstName', 'CasAppAdmLastName', 'CasAppAdmAccount', 'CasAppAdmPassword', 'CasAppAdmProfile'];

                // Default for Administrator //
                $this->dataEntry['CasAppAdmProfile'] = $dataRegister['Register']['CA']['CasAppAdmProfile'];

                if ($isOK) {
                    $isOK = $this->checkRequiredField($fieldsValidation);
                }
                if ($isOK) {
                    $isOK = $this->checkLengthField($fieldsValidation);
                }
                if ($isOK) {
                    $isOK = $this->checkPassField(['CasAppAdmPassword']);
                }
                if ($isOK) {
                    $this->saveRegistration($fieldsValidation, 'CA', $dataRegister);

                    header("Location: /Support/CP/Show/3");
                    exit;
                }
            }
        }

        if (isset($_POST['btnConfirmFinish'])) {
            if ($id == '3') {
                $fieldsValidation = ['CasAppSupFirstName', 'CasAppSupLastName', 'CasAppSupAccount', 'CasAppSupPassword', 'CasAppSupProfile'];

                // Default for Support //
                $this->dataEntry['CasAppSupProfile'] = $dataRegister['Register']['CS']['CasAppSupProfile'];

                if ($isOK) {
                    $isOK = $this->checkRequiredField($fieldsValidation);
                }
                if ($isOK) {
                    $isOK = $this->checkLengthField($fieldsValidation);
                }
                if ($isOK) {
                    $isOK = $this->checkPassField(['CasAppSupPassword']);
                }
                if ($isOK) {
                    $this->saveRegistration($fieldsValidation, 'CS', $dataRegister);

                    // header("Location: /Support");
                    // exit;

                    $this->applyRegistration();
                    
                    header("Location: /Support/CP/Show/3");
                    exit;
                }
            }
        }

        foreach ($fields as $field) {
            if (isset($dataRegister['Register']['VA'][$field])) {
                $this->dataEntry[$field] = $dataRegister['Register']['VA'][$field];
            }
            if (isset($dataRegister['Register']['CA'][$field])) {
                $this->dataEntry[$field] = $dataRegister['Register']['CA'][$field];
            }
            if (isset($dataRegister['Register']['CS'][$field])) {
                $this->dataEntry[$field] = $dataRegister['Register']['CS'][$field];
            }
        }

        // clear password //
        $dataRegister['Register']['CA']['CasAppAdmPassword'] = '';
        $this->dataEntry['CasAppAdmPassword'] = '';

        $dataRegister['Register']['CS']['CasAppSupPassword'] = '';
        $this->dataEntry['CasAppSupPassword'] = '';

        /**
         * Format Data
         */
        $this->formatDataOnForm();

        /**
         * Form Design
         */
        if (in_array($id, ['', '0', '1', '2', '3'])) {
            // Step Start //
            if ($id == '0' || empty($id)) {
                $this->formDesign['TransMode'] = 'StartPage';
                $this->formDesignOnForm($this->formDesign['TransMode'], 0, 'CPViewWizardStepStart.php', [$id]);
            }
            // Variáveis Ambiente //
            if ($id == '1') {
                $this->formDesign['TransMode'] = 'PreviusPageAndNextPage';
                $this->formDesignOnForm($this->formDesign['TransMode'], 1, 'CPViewWizardStepVA.php', [$id]);
            }
            // Conta Administrador //
            if ($id == '2') {
                $this->formDesign['TransMode'] = 'PreviusPageAndNextPage';
                $this->formDesignOnForm($this->formDesign['TransMode'], 2, 'CPViewWizardStepCA.php', [$id]);
            }
            // Conta Suporte //
            if ($id == '3') {
                $this->formDesign['TransMode'] = 'PreviusPageAndFinish';
                $this->formDesignOnForm($this->formDesign['TransMode'], 3, 'CPViewWizardStepCS.php', [$id]);
            }
        } else {
            $this->pageNotFound();
            exit;
        }

        $this->getDataCards($id);

        /**
         * Show Screen
         */
        $data = array(
            'FormDesign' => $this->formDesign,
            'FormData' => FormData::secFields($fields, $this->dataEntry),
        );

        $this->view('SBAdmin/Support/CPView', $data);
    }

    private function formatDataOnForm()
    {
        if (isset($this->dataEntry['ProgramsInModule'])) {
            foreach ($this->dataEntry['ProgramsInModule'] as $key => $value) {
                $this->dataEntry['ProgramsInModule'][$key]['CasPrgBlq'] = FormatData::transformSelectionSN($value['CasPrgBlq'], true);
            }
        }
    }

    private function getDataCards($id)
    {
        $dataItensMenu = json_decode(PanelMenuSupportControls::getDataMenu());

        foreach ($dataItensMenu->PanelMenuSupport as $value) {

            if ($id == '0') {
                if ($value->Program == 'VA') {
                    $this->formDesign['Card']['VA']['Title'] = $value->Title;
                    $this->formDesign['Card']['VA']['Description'] = $value->Description;
                    $this->formDesign['Card']['VA']['Icon'] = $value->Icon;
                }
                if ($value->Program == 'CA') {
                    $this->formDesign['Card']['CA']['Title'] = $value->Title;
                    $this->formDesign['Card']['CA']['Description'] = $value->Description;
                    $this->formDesign['Card']['CA']['Icon'] = $value->Icon;
                }
                if ($value->Program == 'CS') {
                    $this->formDesign['Card']['CS']['Title'] = $value->Title;
                    $this->formDesign['Card']['CS']['Description'] = $value->Description;
                    $this->formDesign['Card']['CS']['Icon'] = $value->Icon;
                }
            } else {
                $this->formDesign['Card']['Title'] = $value->Title;
                $this->formDesign['Card']['Description'] = $value->Description;
                $this->formDesign['Card']['Icon'] = $value->Icon;
                if ($value->Program == 'VA' && $id == '1') {
                    break;
                }
                if ($value->Program == 'CA' && $id == '2') {
                    break;
                }
                if ($value->Program == 'CS' && $id == '3') {
                    break;
                }
            }
        }
    }

    private function checkRequiredField($fields)
    {
        foreach ($fields as $item) {
            if (CasCPModel::FIELDS_MD[$item]['Type'] == 'string') {
                if (trim($this->dataEntry[$item]) == '') {
                    array_push($this->messages, $this->message->getMessage(1, 'Message', 'Campo de preenchimento obrigatório está vazio! (' . CasCPModel::FIELDS_MD[$item]['LongLabel'] . ')'));
                    return false;
                }
            }
        }

        return true;
    }

    private function checkLengthField($fields)
    {
        foreach ($fields as $item) {
            if (CasCPModel::FIELDS_MD[$item]['Type'] == 'string') {
                if (strlen($this->dataEntry[$item]) > CasCPModel::FIELDS_MD[$item]['Length']) {
                    array_push($this->messages, $this->message->getMessage(1, 'Message', 'Campo de preenchimento muito longo! (' . CasCPModel::FIELDS_MD[$item]['LongLabel'] . ')'));
                    return false;
                }
            }
        }

        return true;
    }

    private function checkKeyField($fields)
    {
        $requiredLength = 36;

        foreach ($fields as $item) {
            if (CasCPModel::FIELDS_MD[$item]['Type'] == 'string') {
                if (strlen($this->dataEntry[$item]) != $requiredLength) {
                    array_push($this->messages, $this->message->getMessage(1, 'Message', 'Campo de preenchimento deve conter exatamente ' . $requiredLength . ' caracteres! (' . CasCPModel::FIELDS_MD[$item]['LongLabel'] . ')'));
                    return false;
                }
            }
        }

        return true;
    }

    private function checkPassField($fields)
    {
        $obAuthClass = new AuthClass();

        foreach ($fields as $item) {
            if (CasCPModel::FIELDS_MD[$item]['Type'] == 'string') {
                $resultPass = $obAuthClass->validatePasswordRule($this->dataEntry[$item]);
                if ($resultPass['Code'] != 0) {
                    array_push($this->messages, $resultPass);
                    return false;
                } else {
                    $this->dataEntry[$item] = md5($this->dataEntry[$item]);
                }
            }
        }

        return true;
    }

    public function saveRegistration(array $fieldsValidation, string $step, array $dataRegister): void
    {
        $path = RegisterApplication::getPath();
        foreach ($fieldsValidation as $field) {
            $dataRegister['Register'][$step][$field] = $this->dataEntry[$field];
        }

        RegisterApplication::generateFileRegister($path, json_encode($dataRegister));
    }

    public function applyRegistration(): void
    {
        $data_string = RegisterApplication::getData();
        $data = json_decode($data_string, true);

        // apply file .env //
        $instance = Config::getInstance();
        $envs = $instance->getEnvs();

        // apply settings //
        $envs['APP_NAME'] = $data['Register']['VA']['CasAppName'];
        $envs['APP_KEY'] = $data['Register']['VA']['CasAppKey'];
        $envs['APP_TOKEN'] = $data['Register']['VA']['CasAppToken'];
        $envs['APP_ENV'] = $data['Register']['VA']['CasAppEnv'];
        $envs['APP_URL'] = $data['Register']['VA']['CasAppUrl'];

        // fix //
        $envs['APP_DEBUG'] = 'false';
        $envs['MONITORING_QUERY'] = 'false';
        $envs['STATIC_AUTHETICATION'] = 'true';
        $envs['MAIL_SERVICE'] = 'false';
        $envs['API_MANAGER_URL'] = $data['Register']['VA']['CasAppUrl'] . '/auth';

        $dataContent = '';
        foreach ($envs as $key => $value) {
            $dataContent .= $key . '=' . $value . PHP_EOL;
        }

        Config::writeConfig($dataContent);
        
        // apply file UserAccounts //

        $obRegisterClass = new RegisterClass();

        // admin //
        $data_account = array();
        $data_account['FirstName'] = $data['Register']['CA']['CasAppAdmFirstName'];
        $data_account['LastName'] = $data['Register']['CA']['CasAppAdmLastName'];
        $data_account['Account'] = $data['Register']['CA']['CasAppAdmAccount'];
        $data_account['Password'] = $data['Register']['CA']['CasAppAdmPassword'];
        $data_account['PasswordConfirm'] = $data['Register']['CA']['CasAppAdmPassword'];

        $result = $obRegisterClass->createAccount($data_account);

        // support //
        $data_account = array();
        $data_account['FirstName'] = $data['Register']['CS']['CasAppSupFirstName'];
        $data_account['LastName'] = $data['Register']['CS']['CasAppSupLastName'];
        $data_account['Account'] = $data['Register']['CS']['CasAppSupAccount'];
        $data_account['Password'] = $data['Register']['CS']['CasAppSupPassword'];
        $data_account['PasswordConfirm'] = $data['Register']['CS']['CasAppSupPassword'];

        $result = $obRegisterClass->createAccount($data_account);

        // Start Install //
        // todo... //
    }

    private function formDesignOnForm($transMode, $current, $file, $keys)
    {
        $urlKeys = implode('/', $keys);
        $nextPage = 1 + $current;
        $previusPage = intval($current) - 1;

        $this->formDesign['Tabs']['Current'] = $current;
        $this->formDesign['Tabs']['LoadFile'] = "App/Views/SBAdmin/Support/" . $file;
        $this->formDesign['Tabs']['Items'][0]['Link'] = '/Support/CP';
        $this->formDesign['Tabs']['TotalPages'] = 3;
        $this->formDesign['Tabs']['ProgressBar'] = round(($current / $this->formDesign['Tabs']['TotalPages']) * 100); // min:0  max:100 //

        $this->formDesign['Fields'] = array_merge(CasCPModel::FIELDS_MD, CasPrgModel::FIELDS_MD);

        switch ($transMode) {
            case 'StartPage':
                break;

            case 'PreviusPageAndNextPage':
                $this->formDesign['Tabs']['Items'][$current]['Link'] = "/Support/CP/Show/{$current}";
                $this->formDesign['Tabs']['Items']['LinkPreviusPage'] = "/Support/CP/Show/{$previusPage}";
                break;

            case 'PreviusPageAndFinish':
                $this->formDesign['Tabs']['Items'][$current]['Link'] = "/Support/CP/Show/{$current}";
                $this->formDesign['Tabs']['Items']['LinkPreviusPage'] = "/Support/CP/Show/{$previusPage}";
                break;

            default:
                $this->formDesign['Tabs']['Items']['LinkPreviusPage'] = "/Support/CP";
                $this->formDesign['Tabs']['Items']['LinkNextPage'] = "/Support/CP";
                break;
        }

        if ($this->messages) {
            $this->formDesign['Message'] = $this->messages[0];
        }
    }
}
