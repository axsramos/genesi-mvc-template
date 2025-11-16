<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Shared\MessageDictionary;
use App\Class\Pattern\FormDesign;
use App\Class\Auth\LoginClass;
use App\Core\AuthSession;

class Login extends Controller
{
  private $formDesign;
  private $dataEntry;
  private const FIELDS = ['inputEmail', 'inputPassword'];

  public function __construct()
  {
    $this->message = new MessageDictionary();
    $this->formDesign = FormDesign::secMessage();
  }

  public function index()
  {
    $this->dataEntry = $this->getDataInput($this::FIELDS);

    $obLoginClass = new LoginClass();
    $obLoginClass->logout();

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

    $data = array(
      'FormDesign' => $this->formDesign,
    );

    $this->view('SBAdmin/LoginView', $data);
  }
}
