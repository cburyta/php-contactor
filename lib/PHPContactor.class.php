<?php

class PHPContactor
{
  /**
   * Store settings that are used to control the form.
   * @var array
   */
  private $settings = array();

  /**
   * Store the values that will be posted here
   * @var array
   */
  private $values;

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
   * Check the form to see if it's valid or not
   */
  public function submittedAndValid()
  {
    // todo: check form was submitted, then validate
  }

  /**
   * Pull the values from the POST or GET request and send the email.
   */
  public function process()
  {
    // todo: process the form, get the values and send an email
  }
}
