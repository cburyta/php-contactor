<?php

require_once('PHPContactor.class.php');

// MOST SETTINGS WOULD GO HERE
/// E.G. THE FROM ADDRESS, CAPTCHA KEYS, SUBJECT LINES ETC...
$settings = array(
  'form_name'        => 'contactForm', // this need to be in sync with the form name and the form fields
  'to_emails'        => 'To Name<to_name@example.com>,To Name 2<to_name_2@example.com>',
  'subject'          => 'Subject line here',
  'captcha_service'  => 'recaptcha',
  'recaptcha_api'    => 'API-KEY-HERE',
  'success_redirect' => '/thankyou-page', // could be used to redirect to to any page on success
  'error_format'     => '<div class="error">%%error%%</div>', // this would provide easy customization of error messages if needed
  'error_class'      => 'error', // if the field has an error, then this is the class that's returned in the printErrorClass method
);

// Required fields here
// key == name of the field (e.g. 'email' for the field with name="contactForm[name]")
$required = array(
  'name' => true,
  'email' => true,
  'phone' => true,
);

//
// SETUP THE FORM PROCESSING CLASS
//
$cform = new PHPContactor($settings, $required);  // FormName needs to be given as defined in the HTML

// PROCESS THE FORM, SENDING EMAILS OR WHATEVER THE SETTINGS ARE SETUP TO DO
if($cform->submittedAndValid())
{
  $cform->process(); // this would trigger emails, and would also redirect to the "success_redirect" page.
}