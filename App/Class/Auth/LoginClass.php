<?php

namespace App\Class\Auth;

use App\Core\Config;
use App\Class\Auth\AuthClass;

class LoginClass extends AuthClass
{
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
                            if ($item->Password == $user_pass) {
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
                            if ($item->Password == $user_pass) {
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
        
        /**
         * Log Error
         */
        if ($result['Code'] > 0) {
            $logData = array('code' => $result['Code'], 'description' => $result['Description'], 'input' => array('account' => $account_email));
            self::setLog(json_encode($logData), 'login_error', 'Others');
        }

        return $result;
    }
}
