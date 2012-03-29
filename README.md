# PHP Contactor

A php contact form that's easy to reuse and provides configurability.

---

## Usage - plan 1

    <?php
    
    //
    // setup the form
    //
    require_once('PHPContactor.php');
    
    $cformSettings = array();
    
    $cform = new PHPContactor();
    
    $cform->addField('name', 'Name', 'text');              // set field name, label, field type etc...
    $cform->setValidationFor('name', 'required', true);    // could set up validation per field
    $cform->addField('email', 'Email Address', 'input');   // add email field
    $cform->setValidationFor('email', 'email');            // set validation for email field to be an email address
    $cform->addCaptcha('recaptcha', 'RECAPTCHA-API-KEY');  // add captcha field for the recaptcha service
    $cform->addField('submit', 'Send Contact');            // add submit button
    
    //
    // look to see if the form was submitted
    //
    if($cform->wasSubmitted())
    {
      $errors = $cform->getErrors();
    
      // no errors? process
      if(!$errors)
      {
        // setup email from / subject
        // then send email
        $cform->setupEmails('from', 'subject');
        $cform->sendEmails(array('email1@example.com', 'email2@example.com');
      }
    }
    
    <html>
      <title>Title</title>
    </html>
    <body>
      
      <?php if($errors): // print errors if we have them ?>
        <?php foreach($errors as $errorMessage): ?>
          <div class="error"><?php print $errorMessage ?></div>
        <?php endforeach; ?>
      <?php endif ?>
      
      <?php print $cform->getFormMarkup(); // print the form ?>
      
    </body>
    </html>

---

## Usage - plan 2

    <?php
    
    require_once('PHPContactor.php');
    
    // MOST SETTINGS WOULD GO HERE
    /// E.G. THE FROM ADDRESS, CAPTCHA KEYS, SUBJECT LINES ETC...
    $settings = array(
      'form_name'        => 'contactForm', // this need to be in sync with the form name and the form fields
      'to_emails'         => 'To Name<to_name@example.com>,To Name 2<to_name_2@example.com>',
      'subject'          => 'Subject line here',
      'captcha_service'  => 'recaptcha',
      'recaptcha_api'    => 'API-KEY-HERE',
      'success_redirect' => '/thankyou-page', // could be used to redirect to to any page on success
      'error_format'     => '<div class="error">%%error%%</div>', // this would provide easy customization of error messages if needed
    );
    
    // SETUP THE FORM PROCESSING CLASS
    $cform = new PHPContactor($settings);  // FormName needs to be given as defined in the HTML
    
    // PROCESS THE FORM, SENDING EMAILS OR WHATEVER THE SETTINGS ARE SETUP TO DO
    if($cform->submittedAndValid())
    {
      $cform->process(); // this would trigger emails
    }
    
    ?>
    
    <html>
      <title>Title</title>
    </html>
    <body>
      
      <!-- ERROR PRINTING METHOD ONE - GROUP -->
      <?php if($errors): ?>
        <?php foreach($errors as $errorMessage): ?>
          <div class="error"><?php print $errorMessage ?></div>
        <?php endforeach; ?>
      <?php endif ?>
      
      <!--
        IMPORTANT: form field NAME values need to be named correctly for the form to pick them up,
        the format is FormName[FieldName], so if the form name is contactForm, then the fields are
        contactForm[name], contatForm[email], contactForm[description]
        however, ID's, Class's, and other attribs can be whatever, giving full control over the markup
        
        the use of the $cform->printError('fieldname') would be smart enough to ONLY print an error if
        there is actually an error. Same goes for the $cform->printErrorClass('fieldname'), so you could
        optionally do things like hilight error input fields with a red border with CSS.
      -->
      
      <form name="contactForm" action="submit.php" method="post">
        
        <!-- SET YOUR REQUIRED FIELDS HERE -->
        <input type="hidden" name="_required" value="name, message" />
        
        <label for="field_name">Name:</label>
        <input type="text" name="contactForm[name]" id="field_name" class="someclass <?php $cform->printErrorClass('name') ?>" />
        
        <!-- ERROR PRINTING METHOD TWO - INLINE -->
        <?php $cform->printErrorMessage('name'); ?>
        
        <label for="field_message">Message:</label>
        <textarea name="contactForm[message]" id="field_message"></textarea>
        
        <label for="field_other">Other:</label>
        <textarea name="contactForm[other]" id="field_other"></textarea>
        
        <!-- USE THE PHP FORM TO PRINT YOUR CAPTCHA -->
        <?php print $cform->getCaptcha(); ?>
        
        <input type="submit" name="submit" />
        
      </form>
      
    </body>
    </html>

---
