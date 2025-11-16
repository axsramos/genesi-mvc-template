<?php

namespace App\Class\Auth;

use App\Shared\MessageDictionary;
use App\Core\AuthSession;
use App\Core\Config;

class AuthClass
{
    protected $message;

    use \App\Traits\LogToFile;

    public function __construct()
    {
        $this->message = new MessageDictionary();
    }

    public function logout(): void
    {
        AuthSession::logout();
    }

    protected function setSessionAuth(string $user_auth, object $user_account): void
    {
        // Keep data current //
        foreach ($user_account as $key => $value) {
            AuthSession::set($key, $value);
        }
        /*
        [USR_ID] => anonymous
        [USR_LOGGED] => anonymous
        [SSW_ID] => 7g519604hkc8lsslimqmj4prsq
        [USR_AUTH] => 728506a8fa51ec20e2459214c41a281c
        */

        // Apply new data //
        AuthSession::set('USR_AUTH', $user_auth);

        // AUTHENTICATION_SESSION_LIMIT //
        AuthSession::set('EXPIRES', time() + Config::$AUTHENTICATION_SESSION_LIMIT);
    }

    protected function newTokenPassword(): string
    {
        $token = strval(random_int(100000, 999999));

        return trim($token);
    }

    public function validatePasswordRule(string $password): array
    {
        $rulesPassword = Config::getRulesPassword();

        $library_alfa_upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $library_alfa_lower = 'abcdefghijklmnopqrstuvwxyz';
        $library_numbers = '0123456789';
        $library_specialchars = '@!#$%&*()-_=+[]{}\|;:<>?/,.^~`';
        $quant_chars = strlen(trim($password));

        /**
         * Check Rules Password
         */
        $next = false;

        if ($quant_chars >= $rulesPassword['MinimumLength']) {
            $next = true;
        }

        if ($next) {
            if ($rulesPassword['RequireUppercase']) {
                $next = false;
                for ($i = 0; $i < $quant_chars; $i++) {
                    if (str_contains($library_alfa_upper, $password[$i])) {
                        $next = true;
                        break;
                    }
                }
            }
        }

        if ($next) {
            if ($rulesPassword['RequireLowercase']) {
                $next = false;
                for ($i = 0; $i < $quant_chars; $i++) {
                    if (str_contains($library_alfa_lower, $password[$i])) {
                        $next = true;
                        break;
                    }
                }
            }
        }

        if ($next) {
            if ($rulesPassword['RequireNumbers']) {
                $next = false;
                for ($i = 0; $i < $quant_chars; $i++) {
                    if (str_contains($library_numbers, $password[$i])) {
                        $next = true;
                        break;
                    }
                }
            }
        }

        if ($next) {
            if ($rulesPassword['RequireSimbols']) {
                $next = false;
                for ($i = 0; $i < $quant_chars; $i++) {
                    if (str_contains($library_specialchars, $password[$i])) {
                        $next = true;
                        break;
                    }
                }
            }
        }

        if ($next) {
            $result = $this->message->getMessage(0);
        } else {
            $msg = "Senha: Mímino {$rulesPassword['MinimumLength']} dígitos. Verifique as regras: ";
            if ($rulesPassword['RequireUppercase']) {
                $msg .= " letras maiúsculas; ";
            }
            if ($rulesPassword['RequireLowercase']) {
                $msg .= " letras minúsculas; ";
            }
            if ($rulesPassword['RequireNumbers']) {
                $msg .= " números; ";
            }
            if ($rulesPassword['RequireSimbols']) {
                $msg .= " caracteres especiais;";
            }
            $result = $this->message->getMessage(1, 'Message', $msg);

            /**
             * Log Error
             */
            $logData = array('code' => $result['Code'], 'description' => $result['Description']);
            self::setLog(json_encode($logData), 'register_error', 'Others');
        }

        return $result;
    }

    public function permissions(string $prg_id, bool $cached = true): array
    {
        $dataPermissions = [];

        if (AuthSession::get()['EXPIRES'] < time()) {
            AuthSession::logout();
            return $dataPermissions; // session expired //
        }

        $dataPermissions = ['AUTHORIZED']; // for static authentication, descontinue in the next version //

        /**
         * check permission - next version
         */
        // if ($cached) {
        //     if (isset(AuthSession::get()['AUTHORIZATION']['PROGRAMS'][$prg_id]['FUNCIONALITIES'])) {
        //         $dataPermissions = AuthSession::get()['AUTHORIZATION']['PROGRAMS'][$prg_id]['FUNCIONALITIES'];
        //     }
        // }

        // if (empty($dataPermissions)) {
        //     $authorization = AuthSession::get()['AUTHORIZATION'];
        //     AuthSession::set('AUTHORIZATION', $authorization);
        // }

        return $dataPermissions;
    }
}
