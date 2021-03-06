<?php

// require the setup file
require_once('../lib/PHPContactor.setup.php')

?>
<html>
  <title>Title</title>
  <style type="text/css">
    .error { border:1px solid red; }
  </style>
</html>
<body>

	<!--
	IMPORTANT: form field NAME values need to be named correctly for the form to pick them up,
	the format is FormName[FieldName], so if the form name is contactForm, then the fields are
	contactForm[name], contatForm[email], contactForm[description]
	however, ID's, Class's, and other attribs can be whatever, giving full control over the markup

	the use of the $cform->printError('fieldname') would be smart enough to ONLY print an error if
	there is actually an error. Same goes for the $cform->printErrorClass('fieldname'), so you could
	optionally do things like hilight error input fields with a red border with CSS.
	-->

  <form name="contactForm" action="" method="post">

    <!-- ERROR PRINTING METHOD ONE - GROUP -->
    <?php /*
    <?php if($cform->hasErrors()): ?>
      <?php foreach($cform->getErrors() as $errorMessage): ?>
        <div class="error"><?php print $errorMessage ?></div>
      <?php endforeach; ?>
    <?php endif ?>
    */ ?>

    <?php $cform->printErrorMessages() ?>

    <div>
      <label for="field_name">Name:</label>
      <input type="text" name="contactForm[name]" id="field_name" value="<?php $cform->printCurrentValue('name') ?>" class="someclass <?php $cform->printErrorClass('name') ?>" />
    </div>

    <div>
      <label for="field_email">Email:</label>
      <input type="text" name="contactForm[email]" id="field_email" value="<?php $cform->printCurrentValue('email') ?>" class="someclass <?php $cform->printErrorClass('email') ?>" />
    </div>

    <div>
      <label for="field_phone">Phone:</label>
      <input type="text" name="contactForm[phone]" id="field_phone" value="<?php $cform->printCurrentValue('phone') ?>" class="someclass <?php $cform->printErrorClass('phone') ?>" />
    </div>

    <div>
      <label for="field_subject">Subject:</label>
      <select name="contactForm[subject]" id="field_other">
        <?php $cform->printOptionElements('subject', array('General', 'Website Help', 'Other')); ?>
      </select>
    </div>

    <div>
      <label for="field_message">Message:</label>
      <textarea name="contactForm[message]" id="field_message"><?php $cform->printCurrentValue('message') ?></textarea>
    </div>

    <!-- USE THE PHP FORM TO PRINT YOUR CAPTCHA -->
    <div>
      <?php print $cform->printCaptcha(); ?>
    </div>

    <div>
      <input type="submit" name="submit" />
    </div>

  </form>

</body>
</html>
