<?php

// require the setup file
require_once('lib/PHPContactor.setup.php')

?>
<html>
  <title>Title</title>
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

	<form name="contactForm" action="lib/PHPContactor.setup.php" method="post">

		<!-- ERROR PRINTING METHOD ONE - GROUP -->
		<?php if($errors): ?>
			<?php foreach($errors as $errorMessage): ?>
				<div class="error"><?php print $errorMessage ?></div>
			<?php endforeach; ?>
		<?php endif ?>

		<!-- SET YOUR REQUIRED FIELDS HERE -->
		<input type="hidden" name="_required" value="name, message" />

		<!-- ERROR PRINTING METHOD TWO - INLINE -->
		<label for="field_name">Name:</label>
		<input type="text" name="contactForm[name]" id="field_name" class="someclass <?php $cform->printErrorClass('name') ?>" />
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
