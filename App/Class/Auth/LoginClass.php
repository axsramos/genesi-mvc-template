<?php

namespace App\Class\Auth;

use App\Core\Config;
use App\Class\Auth\AuthClass;
use App\Core\AuthSession;
use App\Models\CAS\CasUsrModel;
use App\Models\CAS\CasRpsModel;
use App\Models\CAS\CasRpuModel;
use App\Models\CAS\CasSwbModel;
use App\Models\CAS\CasTusModel;
use App\Models\CAS\CasWksModel;
use App\Helpers\ObtainBrowserName;
use App\Helpers\ObtainClientInfo;
use App\Core\ServiceMail;
use Google\Service\ToolResults\Any;

class LoginClass extends AuthClass
{
    private $social_login = false;
    private $create_account = false;
    private $avatar_user = '';  // Discontinue when adding a field to the CasUsr table. //

    public function __construct()
    {
        return parent::__construct();
        $this->avatar_user = Config::getPreferences('avatar_user'); // Discontinue when adding a field to the CasUsr table. //
    }

    public function login(string $account_email, string $account_password, string $swap = ''): array
    {
        // validate form data //
        if (empty(trim($account_email)) || empty(trim($account_password))) {
            return $this->message->getMessage(600);
        }

        $result = (bool) filter_var($account_email, FILTER_VALIDATE_EMAIL);
        if (!$result) {
            return $this->message->getMessage(614);
        }

        // swap -> recebe o id do repositório a ser trocado //
        // troca por swap ocorre com o usuario já autenticado //
        // user_auth quando account_email é o repositório principal do usuario //
        // user_auth quando swap é o repositório a ser trocado //
        $user_auth = (empty($swap) ? md5(strtolower($account_email)) : $swap);
        $user_pass = (empty($swap) ? md5($account_password) : $account_password);
        $this->create_account = $this->social_login;

        if (Config::$STATIC_AUTHETICATION === true) {
            /**
             * Static File
             */
            $path = Config::getPathUserAccounts();

            if (file_exists($path)) {
                $dataContent = json_decode(file_get_contents($path));
                $nextSearchProfile = true;

                /**
                 * Search for administrator profile
                 */
                if (isset($dataContent->AdministratorProfile)) {
                    if ($nextSearchProfile) {
                        foreach ($dataContent->AdministratorProfile as $item) {
                            if (strtolower($item->Account) == strtolower($account_email)) {
                                $this->create_account = false;
                                if (($item->Password == $user_pass) || $this->social_login === true) {
                                    $result = $this->message->getMessage(0);
                                    $this->setSessionAuth($user_auth, $item);
                                    $nextSearchProfile = false;
                                    break;
                                }
                            }
                        }
                    }
                }

                /**
                 * Search for other profiles
                 */
                if (isset($dataContent->OtherProfiles)) {
                    if ($nextSearchProfile) {
                        foreach ($dataContent->OtherProfiles as $item) {
                            if (strtolower($item->Account) == strtolower($account_email)) {
                                $this->create_account = false;
                                if (($item->Password == $user_pass) || $this->social_login === true) {
                                    $result = $this->message->getMessage(0);
                                    $this->setSessionAuth($user_auth, $item);
                                    $nextSearchProfile = false;
                                    break;
                                }
                            }
                        }
                    }
                }

                /**
                 * End of Search
                 */
                if ($nextSearchProfile) {
                    $result = $this->message->getMessage(601);
                }
            } else {
                $result = $this->message->getMessage(602);
            }
        } else {
            /**
             * Database
             */
            $result = $this->message->getMessage(602); // standard error message //

            $parts = explode('@', $account_email);

            $obCasUsrModel = new CasUsrModel();
            $obCasUsrModel->CasUsrDmn = '@' . $parts[1];
            $obCasUsrModel->CasUsrLgn = $parts[0];
            $obCasUsrModel->CasUsrPwd = $user_pass;

            /**
             * Validate Login
             */
            if ($obCasUsrModel->checkLogin($this->social_login)) {
                $this->create_account = false;
                if ($obCasUsrModel->CasUsrBlq == 'S') {
                    $result = $this->message->getMessage(615);
                } else {
                    $obCasRpsModel = new CasRpsModel();
                    $obCasRpsModel->setSelectedFields(['CasRpsCod', 'CasRpsDsc', 'CasRpsBlq']);
                    $obCasRpsModel->CasRpsCod = $user_auth;

                    if ($obCasRpsModel->readRegister()) {
                        if ($obCasRpsModel->CasRpsBlq == 'S') {
                            $result = $this->message->getMessage(616);
                        } else {
                            $obCasRpuModel = new CasRpuModel();
                            $obCasRpuModel->CasRpsCod = $user_auth;
                            $obCasRpuModel->CasUsrCod = $obCasUsrModel->CasUsrCod;
                            $obCasRpuModel->setSelectedFields(['CasRpsCod', 'CasUsrCod', 'CasTusCod', 'CasRpuBlq']);

                            if ($obCasRpuModel->readRegister()) {
                                //  when not to return erros //
                                $result = $this->message->getMessage(0);

                                if ($obCasRpuModel->CasRpuBlq == 'S') {
                                    $result = $this->message->getMessage(617);
                                }

                                $obCasTusModel = new CasTusModel();
                                $obCasTusModel->CasRpsCod = $user_auth;
                                $obCasTusModel->CasTusCod = $obCasRpuModel->CasTusCod;
                                $obCasRpuModel->setSelectedFields(['CasTusCod', 'CasTusDsc', 'CasTusBlq', 'CasTusLnk']);
                                if ($obCasTusModel->readRegister()) {
                                    if ($obCasTusModel->CasTusBlq == 'S') {
                                        $result = $this->message->getMessage(618);
                                    }
                                } else {
                                    $result = $this->message->getMessage(619);
                                }
                            } else {
                                $result = $this->message->getMessage(619);
                            }
                        }
                    } else {
                        $result = $this->message->getMessage(619);
                    }
                }

                /**
                 * Create Session
                 */
                if ($result['Code'] == 0 && $result['Type'] == 'SUCCESS') {
                    $repositories = array();

                    // save new terminal //
                    $obCasWksModel = new CasWksModel();
                    $obCasWksModel->setSelectedFields();
                    $obCasWksModel->CasRpsCod = $user_auth;
                    $obCasWksModel->CasWksCod = $user_auth;
                    $obCasWksModel->CasWksDsc = 'WORKSTATION_DEFAULT';
                    $obCasWksModel->CasWksGrp = $user_auth;
                    if ($obCasWksModel->readRegister()) {
                        if ($obCasWksModel->CasWksBlq == 'S') {
                            $result = $this->message->getMessage(621);
                        }
                    } else {
                        $obCasWksModel->CasWksDsc = ObtainClientInfo::getClientHostname();
                        $obCasWksModel->CasWksEip = ObtainClientInfo::getClientIp();
                        $obCasWksModel->CasWksObs = ObtainBrowserName::getLongBrowserName();
                        $r = $obCasWksModel->createRegister();
                    }

                    // get repositories //
                    if ($obCasRpuModel->getAllRepositories()) {
                        $repositories = $obCasRpuModel->getRecords();
                    }

                    // create session on table CasSwb //
                    $obCasSwbModel = new CasSwbModel();
                    $obCasSwbModel->CasRpsCod = $user_auth;
                    $obCasSwbModel->CasSwbCod = AuthSession::get()['SSW_ID'];

                    // when exists session, create new session //
                    if ($obCasSwbModel->readRegister()) {
                        AuthSession::logout();
                        $obCasSwbModel = new CasSwbModel();
                        $obCasSwbModel->CasRpsCod = $user_auth;
                        $obCasSwbModel->CasSwbCod = AuthSession::get()['SSW_ID'];
                    }
                    $obCasSwbModel->CasSwbWks = ObtainClientInfo::getClientHostname(); // Ou getClientIp() se preferir
                    $obCasSwbModel->CasSwbUsu = ObtainClientInfo::getRemoteUser(); // Geralmente vazio, mas tenta
                    $obCasSwbModel->CasSwbBrw = ObtainBrowserName::getBrowserName();
                    if (!$obCasSwbModel->createRegister()) {
                        $result = $this->message->getMessage(620);
                    }

                    if (!$this->validateTerminalLicense()) {
                        $result = $this->message->getMessage(622);
                    }
                }

                if ($result['Code'] == 0 && $result['Type'] == 'SUCCESS') {
                    // apply authenticated user to session. //
                    AuthSession::set('RPS_ID', $obCasRpsModel->CasRpsCod);
                    AuthSession::set('RPS_DSC', $obCasRpsModel->CasRpsDsc);
                    AuthSession::set('USR_ID', $obCasUsrModel->CasUsrCod);
                    AuthSession::set('USR_LOGGED', $obCasUsrModel->CasUsrDsc);
                    AuthSession::set('SSW_ID', $obCasSwbModel->CasSwbCod);
                    AuthSession::set('USR_AUTH', $user_auth);

                    // Formatar data de integração do usuário
                    $integratedDateStr = "Data não disponível";
                    $rawDateValue = $obCasUsrModel->CasUsrAudIns;
                    // Validação alternativa: verifica se a variável não é nula
                    // e se, após remover espaços em branco das extremidades, não é uma string vazia.
                    if ($rawDateValue !== null && trim((string)$rawDateValue) !== '') {
                        try {
                            $dateObject = new \DateTime($rawDateValue);
                            $year = $dateObject->format('Y');
                            $monthNumber = (int)$dateObject->format('n'); // Mês como número (1-12)
                            $monthNamesPt = [
                                1 => 'janeiro',
                                2 => 'fevereiro',
                                3 => 'março',
                                4 => 'abril',
                                5 => 'maio',
                                6 => 'junho',
                                7 => 'julho',
                                8 => 'agosto',
                                9 => 'setembro',
                                10 => 'outubro',
                                11 => 'novembro',
                                12 => 'dezembro'
                            ];
                            $monthName = $monthNamesPt[$monthNumber] ?? ''; // Garante que $monthName seja uma string

                            $integratedDateStr = "Membro desde " . $monthName . " de " . $year . ".";
                        } catch (\Exception $e) {
                            // A data não pôde ser parseada. $integratedDateStr permanece "Data não disponível".
                            // Para depuração, você pode logar o erro e o valor recebido:
                            // error_log("LoginClass: Falha ao formatar CasUsrAudIns. Erro: " . $e->getMessage() . ". Valor: '" . $rawDateValue . "'");
                        }
                    }

                    $presentation = array(
                        'AVATAR' => $this->avatar_user, // add field on feat of the next version //
                        'USER' => $obCasUsrModel->CasUsrDsc,
                        'INTEGRATED' => $integratedDateStr,
                        'ACCOUNT' => $obCasUsrModel->CasUsrLgn . $obCasUsrModel->CasUsrDmn,
                        'ACCESS_TYPE' => $obCasTusModel->CasTusDsc
                    );

                    $item = array();
                    $item['FirstName'] = $obCasUsrModel->CasUsrNme;
                    $item['LastName'] = $obCasUsrModel->CasUsrSnm;
                    $item['Account'] = $obCasUsrModel->CasUsrLgn . $obCasUsrModel->CasUsrDmn;
                    $item['Password'] = $user_pass;
                    // $item['Token'] = '';
                    $item['Repository'] = $user_auth;
                    $item['RPS_ID'] = $obCasRpsModel->CasRpsCod;
                    $item['RPS_DSC'] = $obCasRpsModel->CasRpsDsc;
                    $item['USR_ID'] = $obCasUsrModel->CasUsrCod;
                    $item['USR_LOGGED'] = $obCasUsrModel->CasUsrDsc;
                    $item['PROFILE'] = $obCasTusModel->CasTusDsc;
                    $item['PRESENTATION'] = $presentation;
                    $item['LANGUAGE'] = AuthSession::get()['LANGUAGE'];
                    $item['SSW_ID'] = $obCasSwbModel->CasSwbCod;
                    $item['USR_AUTH'] = $user_auth;
                    $item['EXPIRES'] = (time() + (int) (Config::$AUTHENTICATION_SESSION_LIMIT));
                    $item['HOME_PAGE'] = $obCasTusModel->CasTusLnk;
                    $item['REPOSITORIES'] = $repositories;
                    $item['AUTHORIZATION'] = []; // Preenchido por: AuthClass->permissions() //

                    $this->setSessionAuth($user_auth, (object) $item);
                }
            }
        }

        /**
         * Log Error
         */
        if ($result['Code'] > 0) {
            $logData = array('code' => $result['Code'], 'description' => $result['Description'], 'input' => array('account' => $account_email));
            self::setLog(json_encode($logData), 'login_error', 'Others');
        }

        return $result;
    }

    public function loginSocial(array $credentials, string $dataAuthorized): array
    {
        $this->social_login = true;
        $result = $this->login($credentials['Account'], $credentials['IdSocial']);

        /**
         * Log Error
         */
        if ($result['Code'] == 601 && $this->create_account == true) {
            if ($this->create_account) {
                $register = new RegisterClass();

                $provider_data = array(
                    'Provider' => $credentials['Provider'],
                    'IdSocial'=> $credentials['IdSocial'],
                    'Account' => $credentials['Account'],
                    'FirstName' => $credentials['FirstName'],
                    'LastName' => $credentials['LastName'],
                    'Avatar'=> $credentials['Avatar'],
                    'Gender' => $credentials['Gender'],
                    'Locale' => $credentials['Locale'],
                );
                
                $data_account = array(
                    'FirstName' => $credentials['FirstName'],
                    'LastName' => $credentials['LastName'],
                    'Account' => $credentials['Account'],
                    'Avatar'=> $credentials['Avatar'],
                    'Gender' => ($credentials['Gender'] ?? ''),
                    'Password' => $credentials['IdSocial'],
                    'PasswordConfirm' => $credentials['IdSocial'],
                    'Providers' => [$provider_data],
                );

                $this->avatar_user = $credentials['Avatar']; // Discontinue when adding a field to the CasUsr table. //

                $result = (array) $register->createAccount($data_account);

                if ($result['Code'] == 0 && $result['Type'] == 'SUCCESS') {
                    $serviceMail = new ServiceMail();
                    $r = $serviceMail->sendMailRegister($credentials['FirstName'], $credentials['Account']);

                    header('Location: /Manager/Dashboard');
                    exit;
                }
            }
        }
        if ($result['Code'] > 0) {
            $logData = array('code' => $result['Code'], 'description' => $result['Description'], 'input' => array('account' => $credentials['Account']));
            self::setLog(json_encode($logData), 'login_error', 'Others');
        }

        return $result;
    }

    private function validateTerminalLicense(): bool
    {
        return true;
    }
}
