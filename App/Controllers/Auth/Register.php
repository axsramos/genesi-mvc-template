<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Shared\MessageDictionary;
use App\Class\Pattern\FormDesign;
use App\Class\Pattern\FormData;
use App\Class\Auth\RegisterClass;
use App\Core\ServiceMail;

class Register extends Controller
{
  private $formDesign;
  private $dataEntry;
  private static $FIELDS = ['inputName', 'inputFirstName', 'inputLastName', 'inputEmail', 'inputPassword', 'inputPasswordConfirm'];

  public function __construct()
  {
    $this->message = new MessageDictionary();
    $this->formDesign = FormDesign::secMessage();
  }

  public function index()
  {
    $this->dataEntry = $this->getDataInput(static::$FIELDS);
    $this->dataEntry = FormData::secFields(static::$FIELDS, $this->dataEntry);

    /**
     * Standard Login
     */
    if (isset($_POST['btnConfirm'])) {
      /**
       * Treatment for form with full name or first and last name field
       */
      if (isset($this->dataEntry['inputName']) && !empty(trim($this->dataEntry['inputName']))) {
        $this->dataEntry['inputFirstName'] = explode(' ', $this->dataEntry['inputName'])[0];
        $this->dataEntry['inputLastName'] = explode(' ', $this->dataEntry['inputName'])[1];
      }

      if (empty(trim($this->dataEntry['inputFirstName']))) {
        $this->formDesign['Message'] = $this->message->getMessage(603);
      } else {
        $register = new RegisterClass();
        $resultPass = $register->validatePasswordRule($this->dataEntry['inputPassword']);

        if ($resultPass['Code'] == 0 && $resultPass['Type'] == 'SUCCESS') {
          $data_account = array(
            'FirstName' => $this->dataEntry['inputFirstName'],
            'LastName' => $this->dataEntry['inputLastName'],
            'Account' => $this->dataEntry['inputEmail'],
            'Password' => $this->dataEntry['inputPassword'],
            'PasswordConfirm' => $this->dataEntry['inputPasswordConfirm'],
          );
          
          $result = (array) $register->createAccount($data_account);

          $this->formDesign['Message'] = $result;

          if ($result['Code'] == 0 && $result['Type'] == 'SUCCESS') {
            $serviceMail = new ServiceMail();
            $r = $serviceMail->sendMailRegister($this->dataEntry['inputFirstName'], $this->dataEntry['inputEmail']);
            
            header('Location: /Dashboard');
          }
        } else {
          $this->formDesign['Message'] = $resultPass;
        }
      }
    }

    $data = array(
      'FormDesign' => $this->formDesign,
      'FormData' => FormData::secFields(static::$FIELDS, $this->dataEntry),
    );

    $this->view('SBAdmin/RegisterView', $data);
  }
}
