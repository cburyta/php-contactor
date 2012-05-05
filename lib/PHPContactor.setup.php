<?php

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PHPContactor.class.php');

// MOST SETTINGS WOULD GO HERE
// E.G. THE FROM ADDRESS, CAPTCHA KEYS, SUBJECT LINES ETC...
// Also if your working on dev, you can use a settings.php file to store these in rather than placing your stuff here.
if(file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'settings.php'))
{
  include('settings.php');
}
else
{
  $settings = array(
    'form_name'        => 'contactForm', // this need to be in sync with the form name and the form fields
    'to_emails'        => 'To Name<to_name@example.com>,To Name 2<to_name_2@example.com>',
    'from_name'        => '', // optional, a name for the from header
    'from_email'       => 'email@fromdomain.com', // email address the form will be sent on behalf of
    'subject'          => 'Subject line here',
    'captcha_service'  => 'recaptcha',
    'recaptcha_public_key'  => 'API KEY HERE',
    'recaptcha_private_key' => 'API KEY HERE',
    'success_redirect' => '/thankyou-page', // could be used to redirect to to any page on success
    'error_format'     => '<div class="error">%%error%%</div>', // this would provide easy customization of error messages if needed
    'error_class'      => 'error', // if the field has an error, then this is the class that's returned in the printErrorClass method
  );

  // Required fields here
  // key == name of the field (e.g. 'email' for the field with name="contactForm[name]")
  $required = array(
    'name' => 'required',
    'email' => 'email',
    'phone' => 'required',
  );
}

//
// SETUP THE FORM PROCESSING CLASS
//
$cform = new PHPContactor($settings, $required);  // FormName needs to be given as defined in the HTML

// PROCESS THE FORM, SENDING EMAILS OR WHATEVER THE SETTINGS ARE SETUP TO DO
if($cform->submittedAndValid())
{
  $cform->process(); // this would trigger emails, and would also redirect to the "success_redirect" page.
}