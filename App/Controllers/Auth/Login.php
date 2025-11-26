<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Shared\MessageDictionary;
use App\Class\Pattern\FormDesign;
use App\Class\Auth\LoginClass;
use App\Core\AuthSession;
use App\Models\CAS\CasUsrModel;
use App\Class\Auth\OnGoogleAuth;
use App\Core\Config;

class Login extends Controller
{
  private $formDesign;
  private $dataEntry;
  private $obOnGoogleAuth;
  private const FIELDS = ['inputEmail', 'inputPassword'];

  public function __construct()
  {
    $this->message = new MessageDictionary();
    $this->formDesign = FormDesign::secMessage();

    $this->obOnGoogleAuth = new OnGoogleAuth();
    $this->obOnGoogleAuth->init();
  }

  public function index()
  {
    $this->dataEntry = $this->getDataInput($this::FIELDS);

    $obLoginClass = new LoginClass();
    $obLoginClass->logout();

    /**
     * Standard Login
     */
    if (isset($_POST['btnConfirm'])) {
      $result = $obLoginClass->login($this->dataEntry['inputEmail'], $this->dataEntry['inputPassword']);

      $this->formDesign['Message'] = $result;

      if ($result['Code'] == 0 && $result['Type'] == 'SUCCESS') {
        if (isset(AuthSession::get()['HOME_PAGE'])) {
          header("Location: " . AuthSession::get()['HOME_PAGE']);
        } else {
          header("Location: /Home");
        }
      }
    }

    /**
     * Social Login - Google
     */
    if (isset($_POST['btnLoginWithGoogle'])) {
      $linkRedirect = $this->obOnGoogleAuth->generateAuthLink();
      if (! empty($linkRedirect)) {
        // Redireciona para o Google. Retorno do login no callback. //
        header("Location: " . $linkRedirect);
        exit;
      }
      $this->formDesign['Message'] = $this->message->getMessage(625);
    }

    $data = array(
      'FormDesign' => $this->formDesign,
    );

    $this->view('SBAdmin/LoginView', $data);
  }

  public function callback(string $data = ''): void
  {
    $obLoginClass = new LoginClass();
    $obLoginClass->logout();

    // API do Google retorna neste callback //
    $dataAuthorized = '';
    $redirectLink = '/Auth/Login';
    $src_avatar = Config::$URL_BASE . Config::getPreferences('avatar_user');
    $label_user = '';
    $label_account = '';
    $provider = 'google';
    
    if ($this->obOnGoogleAuth->authorized($data)) {
      $dataAuthorized = $this->obOnGoogleAuth->getData();
      
      $credentials = $this->setCredentials($provider, $dataAuthorized);
  
      $result = $obLoginClass->loginSocial($credentials, base64_encode(json_encode($dataAuthorized)));
  
      $this->formDesign['Message'] = $result;
  
      if ($result['Code'] == 0 && $result['Type'] == 'SUCCESS') {
        $src_avatar = $credentials['Avatar'];
        $label_user = $credentials['FullName'];
        $label_account = $credentials['Account'];
        if (isset(AuthSession::get()['HOME_PAGE'])) {
          // header("Location: " . AuthSession::get()['HOME_PAGE']);
          $redirectLink = AuthSession::get()['HOME_PAGE'];
        } else {
          // header("Location: /Home");
          $redirectLink = '/Home';
        }
      } 
    } else {
      $this->formDesign['Message'] = $this->message->getMessage(626);
    }

    $dataRturn = array(
      'FormDesign' => $this->formDesign,
      'FormData' => array(
        'Provider' => ucfirst($provider),
        'Avatar' => $src_avatar,
        'LabelUser' => $label_user,
        'LabelAccount' => $label_account,
      ),
      'RedirectLink' => $redirectLink
    );

    $this->view('SBAdmin/LoginSocialView', $dataRturn);
  }

  public function swap($rps_id)
  {
    $obCasUsrModel = new CasUsrModel();
    $obCasUsrModel->setSelectedFields(['CasUsrCod', 'CasUsrDmn', 'CasUsrLgn', 'CasUsrPwd']);
    $obCasUsrModel->CasUsrCod = AuthSession::get()['USR_ID'];

    if ($obCasUsrModel->readRegister()) {
      $account_password = $obCasUsrModel->getRecords()[0]['CasUsrPwd'];
      $account_email = $obCasUsrModel->getRecords()[0]['CasUsrLgn'] . $obCasUsrModel->getRecords()[0]['CasUsrDmn'];

      $obLoginClass = new LoginClass();
      $result = $obLoginClass->login($account_email, $account_password, $rps_id);

      $linkRedirect = '/Home';

      if ($result['Code'] == 0 && $result['Type'] == 'SUCCESS') {
        if (isset(AuthSession::get()['HOME_PAGE']) && !empty(AuthSession::get()['HOME_PAGE'])) {
          $linkRedirect = AuthSession::get()['HOME_PAGE'];
        }
      }

      header('Location: ' . $linkRedirect);
    }
  }

  private function setCredentials(string $provider, array | object $dataAuthorized): array
  {
    $credentials = array();

    switch ($provider) {
      case 'google':
        $credentials = array(
          'Provider' => $provider,
          'IdSocial' => ($dataAuthorized['id'] ?? ''),
          'Account' => ($dataAuthorized['email'] ?? ''),
          'FullName' => ($dataAuthorized['name'] ?? ''),
          'Avatar' => ($dataAuthorized['picture'] ?? ''),
          'Locale' => ($dataAuthorized['locale'] ?? ''),
          'LastName' => ($dataAuthorized['family_name'] ?? ''),
          'FirstName' => ($dataAuthorized['given_name'] ?? ''),
        );
        break;

        /**
         * Others providers
         */
        // case 'facebook':
        //   # code...
        //   break;
    }
    
    return $credentials;
  }
}
