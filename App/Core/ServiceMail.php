<?php

namespace App\Core;

use App\Core\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ServiceMail
{
    use \App\Traits\LogToFile;

    private $mail;
    private $dataMail;
    public $message = '';
    private static $FROM_NAME = null;
    private static $FROM_MAIL = null;

    public function __construct() {}

    public function sendMailRegister($name, $email)
    {
        $path = Config::getPathMailRegister();
        $data_content = '';
        $result = false;

        if (is_file($path)) {
            $data_content = file_get_contents($path);

            $this->mail = new PHPMailer();
            $this->setConfigMail();

            $this->mail->setFrom(self::$FROM_MAIL, self::$FROM_NAME);
            $this->mail->addAddress($email, $name);
            $this->mail->Subject = 'Registro da Conta';

            $this->mail->isHTML(TRUE);
            $data_content = str_replace("**[NOME_DO_CLIENTE]**!", $name, $data_content);
            $this->mail->Body = $data_content;

            if (Config::$MAIL_SERVICE === true) {
                if ($this->mail->send()) {
                    $this->message = 'Mensagem enviada com sucesso.';
                    $result = true;
                } else {
                    $this->message = 'A mensagem não pôde ser enviada. Mailer Error: ' . $this->mail->ErrorInfo;
                    self::setLog($this->message, 'error', 'Mail');
                }
            } else {
                $this->message = 'Mensagem (simulada) enviada com sucesso.';
                self::setLog($this->message, 'info', 'Mail');
                $result = true;
            }
        }

        return $result;
    }

    public function sendMailRecovery($name, $email, $token)
    {
        $path = Config::getPathMailRecovery();
        $data_content = '';
        $result = false;

        if (is_file($path)) {
            $data_content = file_get_contents($path);

            $this->mail = new PHPMailer();
            $this->setConfigMail();

            $this->mail->setFrom(self::$FROM_MAIL, self::$FROM_NAME);
            $this->mail->addAddress($email, $name);
            $this->mail->Subject = 'Recuperar Conta';

            $this->mail->isHTML(TRUE);
            $data_content = str_replace("**[NOME_DO_USUÁRIO]**!", $name, $data_content);
            $data_content = str_replace("**[ACCESS_TOKEN]**!", $token, $data_content);
            $this->mail->Body = $data_content;

            if (Config::$MAIL_SERVICE === true) {
                if ($this->mail->send()) {
                    $this->message = 'Mensagem enviada com sucesso.';
                    $result = true;
                } else {
                    $this->message = 'A mensagem não pôde ser enviada. Mailer Error: ' . $this->mail->ErrorInfo;
                    self::setLog($this->message, 'error', 'Mail');
                }
            } else {
                $this->message = 'Mensagem (simulada) enviada com sucesso.';
                self::setLog($this->message, 'info', 'Mail');
                $result = true;
            }
        } else {
            $this->message = 'Template não localizado. ';
        }

        return $result;
    }

    private function setConfigMail($account = 'default')
    {
        // configurar um SMTP
        switch ($account) {
            case 'support':
                // $this->mail->isSMTP();
                // $this->mail->Host = Config::$MAIL_SUPPORT['MAIL_HOST'];
                // $this->mail->SMTPAuth = Config::$MAIL_SUPPORT['MAIL_AUTH'];
                // $this->mail->Username = Config::$MAIL_SUPPORT['MAIL_USERNAME'];
                // $this->mail->Password = Config::$MAIL_SUPPORT['MAIL_PASSWORD'];
                // $this->mail->SMTPSecure = (Config::$MAIL_SUPPORT['MAIL_ENCRYPTION'] == 'ssl' ? $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS : $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS);
                // $this->mail->Port = Config::$MAIL_SUPPORT['MAIL_PORT'];
                // self::$FROM_NAME = Config::$MAIL_SUPPORT['MAIL_FROM_NAME'];
                // self::$FROM_MAIL = Config::$MAIL_SUPPORT['MAIL_FROM_ADDRESS'];
                break;

            default:
                // 'default' //
                $this->mail->isSMTP();
                $this->mail->Host = Config::$MAIL['MAIL_HOST'];
                $this->mail->SMTPAuth = Config::$MAIL['MAIL_AUTH'];
                $this->mail->Username = Config::$MAIL['MAIL_USERNAME'];
                $this->mail->Password = Config::$MAIL['MAIL_PASSWORD'];
                $this->mail->SMTPSecure = (Config::$MAIL['MAIL_ENCRYPTION'] == 'ssl' ? $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS : $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS);
                $this->mail->Port = Config::$MAIL['MAIL_PORT'];
                self::$FROM_NAME = Config::$MAIL['MAIL_FROM_NAME'];
                self::$FROM_MAIL = Config::$MAIL['MAIL_FROM_ADDRESS'];
                break;
        }
    }
}
