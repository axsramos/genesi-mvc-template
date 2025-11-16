<?php

namespace App\Class\Auth;

use App\Class\Auth\AuthClass;
use App\Core\Config;

class RecoveryClass extends AuthClass
{
    public $name;
    public $token;

    public function generateTokenToRecoverPassword(string $account_account_email): array
    {
        $result = $this->message->getMessage(0);

        // validate form data //
        if (empty(trim($account_account_email))) {
            return $this->message->getMessage(607);
        }

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
                        if (strtolower($item->Account) == strtolower($account_account_email)) {
                            $this->name = $item->FirstName . ' ' . $item->LastName;
                            $this->token = $this->newTokenPassword();
                            $item->Token = $this->token;
                            $result = $this->message->getMessage(0);
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
                        if (strtolower($item->Account) == strtolower($account_account_email)) {
                            $this->name = $item->FirstName . ' ' . $item->LastName;
                            $this->token = $this->newTokenPassword();
                            $item->Token = $this->token;
                            $result = $this->message->getMessage(0);
                            $nextSearchProfile = false;
                        }
                    }
                }
            }

            /**
             * End of Search
             */
            if ($nextSearchProfile) {
                $result = $this->message->getMessage(608);
            } else {
                if (!file_put_contents($path, json_encode($dataContent))) {
                    $result = $this->message->getMessage(610);
                }
            }
        } else {
            $result = $this->message->getMessage(602);
        }

        /**
         * Log Error
         */
        if ($result['Code'] > 0) {
            $logData = array('code' => $result['Code'], 'description' => $result['Description'], 'input' => array('account' => $account_account_email));
            self::setLog(json_encode($logData), 'login_error', 'Others');
        }

        return $result;
    }

    public function updatePassword(string $token, string $account_email, string $account_password, string $account_passwordConfirm): array
    {
        $result = $this->message->getMessage(0);

        if (empty(trim($account_email)) || empty(trim($account_password))) {
            $result = $this->message->getMessage(604);
        } else {
            if ($account_password != $account_passwordConfirm) {
                $result = $this->message->getMessage(605);
            } else {
                /**
                 * Static File
                 */
                $path = Config::getPathUserAccounts();

                if (file_exists($path)) {
                    $dataContent = json_decode(file_get_contents($path));
                    $nextSearchProfile = true;

                    if ($dataContent->AdministratorProfile) {
                        foreach ($dataContent->AdministratorProfile as $item) {
                            if (strtolower($item->Account) == strtolower($account_email)) {
                                if ($token == $item->Token) {
                                    $item->Password = md5($account_password);
                                    $result = $this->message->getMessage(0);
                                } else {
                                    $result = $this->message->getMessage(610);
                                }
                                $nextSearchProfile = false;
                                break;
                            }
                        }
                    }

                    if ($dataContent->OtherProfiles) {
                        if ($nextSearchProfile) {
                            foreach ($dataContent->OtherProfiles as $item) {
                                if (strtolower($item->Account) == strtolower($account_email)) {
                                    if ($token == $item->Token) {
                                        $item->Password = md5($account_password);
                                        $result = $this->message->getMessage(0);
                                    } else {
                                        $result = $this->message->getMessage(610);
                                    }
                                    $nextSearchProfile = false;
                                    break;
                                }
                            }
                        }
                    }

                    if ($nextSearchProfile) {
                        $result = $this->message->getMessage(608);
                    } else {
                        if (!file_put_contents($path, json_encode($dataContent))) {
                            $result = $this->message->getMessage(609);
                        }
                    }
                } else {
                    $result = $this->message->getMessage(602);
                }
            }
        }

        return $result;
    }
}
