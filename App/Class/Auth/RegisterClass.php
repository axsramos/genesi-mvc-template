<?php

namespace App\Class\Auth;

use App\Class\Auth\AuthClass;
use App\Core\AuthSession;
use App\Core\Config;
use App\Models\CAS\CasRpaModel;
use App\Class\Manager\CreateUserRepositoryAccount;
use App\Class\Manager\ApplyApplicationSettings;

class RegisterClass extends AuthClass
{
    public function createAccount(array $data_account): array
    {
        // validate form data //
        if (empty(trim($data_account['FirstName']))) {
            return $this->message->getMessage(604);
        }
        if (empty(trim($data_account['LastName']))) {
            return $this->message->getMessage(604);
        }
        if (empty(trim($data_account['Account']))) {
            return $this->message->getMessage(604);
        }
        if (empty(trim($data_account['Password']))) {
            return $this->message->getMessage(604);
        }
        if (empty(trim($data_account['PasswordConfirm']))) {
            return $this->message->getMessage(604);
        }
        if ($data_account['Password'] != $data_account['PasswordConfirm']) {
            return $this->message->getMessage(605);
        }

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
                            if (strtolower($item->Account) == strtolower($data_account['Account'])) {
                                $result = $this->message->getMessage(606);
                                $nextSearchProfile = false;
                                break;
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
                            if (strtolower($item->Account) == strtolower($data_account['Account'])) {
                                $result = $this->message->getMessage(606);
                                $nextSearchProfile = false;
                                break;
                            }
                        }
                    }
                }

                /**
                 * End of Search. Create new account.
                 */
                if ($nextSearchProfile) {
                    $user_auth = md5(strtolower($data_account['Account']));
                    $new_data_user = $this->generateDataUser($user_auth, $data_account);

                    array_push($dataContent->OtherProfiles, $new_data_user);

                    if (file_put_contents($path, json_encode($dataContent))) {
                        $this->setSessionAuth($user_auth, (object) $new_data_user);
                        $result = $this->message->getMessage(0);
                    }
                }
            } else {
                $result = $this->message->getMessage(602);
            }
        } else {
            $user_auth = md5(strtolower($data_account['Account']));
            $new_data_user = $this->generateDataUser($user_auth, $data_account);

            // configure repository and users account //
            $obCreateUserRepositoryAccount = new CreateUserRepositoryAccount();
            $obCreateUserRepositoryAccount->run($new_data_user);

            // add application //
            $obCasRpaModel = new CasRpaModel();
            $obCasRpaModel->setSelectedFields(['CasRpsCod', 'CasRpaCod', 'CasRpaDsc', 'CasRpaBlq', 'CasRpaGrp']);
            $obCasRpaModel->CasRpsCod = $user_auth;
            $obCasRpaModel->CasAppCod = Config::$APP_KEY;
            if (!$obCasRpaModel->readRegister()) {
                $obCasRpaModel->CasRpaDsc = Config::$APP_NAME;
                $obCasRpaModel->CasRpaBlq = 'N';
                $obCasRpaModel->CasRpaGrp = Config::$APP_NAME;
                $result = $obCasRpaModel->createRegister();
            }

            // configure program settings //
            $obApplyApplicationSettings = new ApplyApplicationSettings();
            $obApplyApplicationSettings->run($user_auth, Config::$APP_KEY);

            // login user //
            $obLoginClass = new LoginClass();
            $result = $obLoginClass->login($data_account['Account'], $data_account['Password'], '');

            $linkRedirect = '/Home';

            if ($result['Code'] == 0 && $result['Type'] == 'SUCCESS') {
                if (isset(AuthSession::get()['HOME_PAGE']) && !empty(AuthSession::get()['HOME_PAGE'])) {
                    $linkRedirect = AuthSession::get()['HOME_PAGE'];
                }
            }

            header('Location: ' . $linkRedirect);
        }

        // create repository //
        $repositoryPath = Config::getPathRepositories() . $user_auth;
        if (!is_dir($repositoryPath)) {
            if (!mkdir($repositoryPath, 0777, true)) {
                $result = $this->message->getMessage(623); // Could not create repository directory
                $dataLog = array('account' => $data_account['Account'], 'user_auth' => $user_auth, 'path' => $repositoryPath, 'message' => $result['Description']);
                $this->setLog(json_encode($dataLog), 'error', 'Others');
            }
        }

        /**
         * Log Error
         */
        if ($result['Code'] > 0) {
            // remove data password for logs //
            unset($data_account['Password']);
            unset($data_account['PasswordConfirm']);

            $logData = array('code' => $result['Code'], 'description' => $result['Description'], 'input' => $data_account);
            self::setLog(json_encode($logData), 'register_error', 'Others');
        }

        return $result;
    }

    private function generateDataUser(string $user_auth, array $data_account): array
    {
        $new_data_user = array();

        foreach ($data_account as $key => $value) {
            // Do not include fields //
            if ($key == 'Password' || $key == 'PasswordConfirm') {
                continue;
            }
            $new_data_user[$key] = $value;
        }

        // apply others fields //
        $new_data_user['Password'] = md5($data_account['Password']);
        $new_data_user['Token'] = $this->newTokenPassword();
        $new_data_user['Repository'] = $user_auth;
        $new_data_user['Avatar'] = ($data_account['Avatar'] ?? Config::getPreferences('avatar_user'));
        $new_data_user['Gender'] = ($data_account['Gender'] ?? '');
        $new_data_user['Providers'] = ($data_account['Providers'] ?? '[]');

        // apply data session //
        $new_data_user['USR_ID'] = $user_auth;
        $new_data_user['USR_LOGGED'] = $data_account['FirstName'] . ' ' . $data_account['LastName'];
        $new_data_user['PROFILE'] = 'USER'; // when includes on $dataContent->OtherProfiles //
        $new_data_user['LANGUAGE'] = AuthSession::get()['LANGUAGE'];
        $new_data_user['SSW_ID'] = $user_auth;
        $new_data_user['USR_AUTH'] = $user_auth;

        return $new_data_user;
    }
}
