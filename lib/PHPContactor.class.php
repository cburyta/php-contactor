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
   * Store the errors if any occure for later display.
   */
  private $errors = array();

  /**
   * Store the values that will be posted here
   * @var array
   */
  private $emailValues;

  /**
   * Setup the project
   */
  public function __construct($settings = array())
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
    $this->settings = array_merge($_default_settings, $settings);
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
    if(isset($_POST[$this->settings['form_name']]))
    {
      $this->values = $_POST[$this->settings['form_name']];
    }

    // empyt means we are not valid
    return (empty($this->values)) ? false : true;

    // @todo: check form was submitted, then validate
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
   * Send the email with the info
   * @todo: right now this is a very basic text email, may
   *        be good to add template support or something later.
   */
  protected function sendEmail()
  {
    // build the message
    $message = "\nEmail form submission.";

    // for every value, we'll add a line.
    foreach($this->values as $name => $value)
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
