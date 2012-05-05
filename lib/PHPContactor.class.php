<?php

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
      'to_emails'         => 'To Name<to_name@example.com>,To Name 2<to_name_2@example.com>',
      'subject'          => 'Subject line here',
      'captcha_service'  => 'recaptcha',
      'recaptcha_api'    => 'API-KEY-HERE',
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
    // @todo: check form was submitted, then validate
  }

  /**
   * Pull the values from the POST or GET request and send the email.
   */
  public function process()
  {
    // @todo: process the form, get the values and send an email
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
}
