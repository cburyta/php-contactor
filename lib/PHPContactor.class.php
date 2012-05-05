<?php

// include recaptcha support
// @todo: if ever we need more support for different captchas,
//        probably a good idea to break includes for captcha libs
//        into sub folders
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'recaptchalib.php');

class PHPContactor
{
  /**
   * Store settings that are used to control the form.
   * @var array
   */
  private $settings = array();

  /**
   * Rules for validating our form values
   * @var array
   */
  private $required = array();

  /**
   * Store the errors if any occure for later display.
   */
  private $errors = array();

  /**
   * Store the values that will be posted here
   * @var array
   */
  private $emailValues = array();

  /**
   * Setup the project
   */
  public function __construct($settings = array(), $required = array())
  {
    // get defaults
    $_default_settings = array(
      'form_name'        => 'contactForm', // this need to be in sync with the form name and the form fields
      'to_emails'        => 'To Name<to_name@example.com>,To Name 2<to_name_2@example.com>',
      'subject'          => 'Subject line here',
      'captcha_service'  => 'recaptcha',
      'recaptcha_public_key'  => '',
      'recaptcha_private_key' => '',
      'success_redirect' => '/thankyou-page', // could be used to redirect to to any page on success
      'error_format'     => '<div class="error">%%error%%</div>', // this would provide easy customization of error messages if needed
    );

    // merge any of the settings into the default
    $this->settings = array_merge($this->settings, $_default_settings, $settings);

    // do the same for required
    $this->required = array_merge($this->required, $required);
  }

  /**
   * Gets an array of errors that can be used if the form needs more info.
   */
  public function getErrors()
  {
    // @todo: get errors if there are any
  }

  /**
   * Check the form to see if it's valid or not
   */
  public function submittedAndValid()
  {
    // if we don't have POST data to check, we're probably
    // not trying to submit a form
    if(!isset($_POST[$this->settings['form_name']]))
    {
      return false;
    }

    // get the values
    $this->emailValues = $_POST[$this->settings['form_name']];

    // validate the fields for the form itself
    $this->validate();

    // validate the captcha fields
    $this->validateCaptcha();

    // if we have any errors, we don't do anything,
    // since we should be calling the validate from the same
    // page that the form is called on, we don't need to redirect
    // or anything yet, the form page submits to itself, and thus
    // we just let it be now
    if(count($this->errors) > 0)
    {
      return false;
    }

    // empty means we are not valid
    return true;

    // @todo: check form was submitted, then validate
  }

  /**
   * Verify and check data against the required options.
   */
  protected function validate()
  {
    foreach($this->emailValues as $name => $value)
    {
      $rules = $this->getValidationRules($name);

      // if we have rules, it needs to be checked
      $valid = $this->validateField($name, $value, $rules);
    }
  }

  /**
   * Validate one field with the ruels provided
   * @param string $name The name of the field without the formname appended.
   * @param string $value
   * @param array|false $rules
   */
  protected function validateField($name, $value, $rules = false)
  {
    // no rules? we're already valid because its an optional field
    if($rules === false)
    {
      return true;
    }

    // check count of existing errors,
    // this setup provides a method for us to validate a form, store
    // errors, and check later to see how many errors
    // this particular field generated.
    // @todo: the errors are set still one per field, so that means one error per field still, add ability to add multiple errors per field
    $existingErrorCount = count($this->errors);

    // switch per the rules that we need to validate for
    foreach($rules as $type => $rule)
    {
      switch($type)
      {
        case 'required':
          if($rule == true && empty($value))
          {
            // add an error
            $this->errors[$name] = sprintf('The %s field is required.', $name);
          }
        break;
      }
    }

    // no errors means a happy form
    return count($this->errors) > $existingErrorCount ? true : false;
  }

  /**
   * Get the rule that we need to validate a rule
   * Note that for items that their validation is a boolean false,
   * they are considered optional.
   * If their required option is a boolean true, we'll format it a bit
   * to keep a consistant array return structure.
   */
  protected function getValidationRules($name)
  {
    // will be used to store the requirement return val
    $requirements = false;

    if(isset($this->required[$name]))
    {
      // get the requirements
      $requirements = $this->required[$name];

      // ensure we have an array, === to true means just required
      if($requirements === true || !is_array($requirements))
      {
        // this is the base requirement structure we'll want to keep consistant
        // @todo: break out the setup so we can have other types of requirements
        // e.g. "required", "email"...
        $requirements = array(
          'required' => true,
        );
      }
    }

    // return false if we get here, since we don't have any valid requirements
    return $requirements;
  }

  /**
   * Pull the values from the POST or GET request and send the email.
   */
  public function process()
  {
    // @todo: process the form, get the values and send an email
    $this->sendEmail();
  }

  /**
   * Print all errors, using the error format supplied in settings.
   * @return string
   */
  public function printErrorMessages()
  {
    foreach($this->errors as $name => $error)
    {
      $this->printErrorMessage($name);
    }
  }

  /**
   * Print one message as defined as by the name given.
   */
  public function printErrorMessage($name)
  {
    if($error = $this->getErrorMessage($name))
    {
      echo str_replace('%%error%%', $error, $this->settings['error_format']);
    }
  }

  /**
   * Get one error message, or return false if it does not exist.
   */
  public function getErrorMessage($name)
  {
    if(array_key_exists($name, $this->errors))
    {
      return $this->errors[$name];
    }
    else
    {
      return false;
    }
  }

  /**
   * Check to see if we have any errors in the queue.
   * @return boolean If there are no items in the errors param, return false. Otherwise return true.
   */
  public function hasErrors()
  {
    return empty($this->errors) ? false : true;
  }

  /**
   * Returns a string that can be used as a class to indicate if the field has an error or not.
   */
  public function printErrorClass($name)
  {
    if($this->getErrorMessage($name))
    {
      return $this->settings['error_class'];
    }
  }

  /**
   * Prints the captcha required for validation.
   */
  public function printCaptcha()
  {
    echo recaptcha_get_html($this->settings['recaptcha_public_key']);
  }

  /**
   * Validate the captcha, sets errors if not valid
   */
  protected function validateCaptcha()
  {
    $resp = recaptcha_check_answer($this->settings['recaptcha_private_key'],
                                   $_SERVER["REMOTE_ADDR"],
                                   $_POST["recaptcha_challenge_field"],
                                   $_POST["recaptcha_response_field"]);

    if (!$resp->is_valid)
    {
      // What happens when the CAPTCHA was entered incorrectly
      $this->errors['_captcha'] = sprintf('The reCAPTCHA wasn\'t entered correctly. Go back and try it again. (reCAPTCHA said: %s)', $resp->error);
      return false;
    }
    else
    {
      return true;
    }
  }

  /**
   * Send the email with the info
   * @todo: right now this is a very basic text email, may
   *        be good to add template support or something later.
   */
  protected function sendEmail()
  {
    // build the message
    $message = "\nEmail form submission.";

    // for every value, we'll add a line.
    foreach($this->emailValues as $name => $value)
    {
      $message .= "\n$name  :  $value";
    }

    // wordwrap at 70 chars
    $message = wordwrap($message, 70);

    // add headers to support from addressi
    $from = (empty($this->settings['from_name'])) ? $from_email : $from_name . ' <' . $from_email . '>';
    $headers = 'From: ' . $from . "\r\n" .
      'Reply-To: ' . $from . "\r\n" .

    // send email
    $sent = mail($this->settings['to_emails'], $this->settings['subject'], $message);

    // redirect to the thank you page
    header('Location: ' . $this->settings['success_redirect']);
    exit;
  }
}
