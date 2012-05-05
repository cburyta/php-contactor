# PHP Contactor

## About

A php contact form that's easy to reuse and provides configurability.

---

## Setup

1. Copy lib folder to your site

The lib folder has most of the magic in it. Also, there is a copy of the recaptcha libs for php.

2. Update PHPContactor.setup.php

That file has settings, and required field definitions. 

3. Build your form

There is an example form in the examples folder. You can use that as a starting point.

You use HTML for most of the form, see below for the requirements when building your form.

---

## Config (PHPContactor.setup.php)

In that file, you'll want to update the

- from_name
- to_emails (comma separated)
- from_name
- from_email
- subject
- recaptcha_public_key (get your free key at https://www.google.com/recaptcha)
- recaptcha_private_key (get your free key at https://www.google.com/recaptcha)

## HTML Form

Your form is mostly straight HTML, but here are the key requirements when building your form.

### 1. Ensure you include PHPContactor.setup.php file corrrectly.

At the top of your file, BEFORE ANY HTML OR WHITESPACE (to make redirects work properlly) you need to add a php require call to the top of the file 

    <?php require_once('lib/PHPContactor.setup.php'); ?>

### 2. HTML Form attribs

The form tag needs to be setup in the following way

- name="contactForm"
- method="post"
- action=""

_The action can actually be non-empty, but the key is that it MUST submit to itself, e.g. if your url to the contact page is http://domain.com/contactform.php, youll need to be sure to point the action to that address (relative /contactform.php or absolute http://domain.com/contactform.php)_

### 3. Text Fields

Example...

    <div>
      <label for="field_name">Name:</label>
      <input type="text" name="contactForm[name]" id="field_name" value="<?php $cform->printCurrentValue('name') ?>" class="someclass <?php $cform->printErrorClass('name') ?>" />
    </div>

Key points are...

- name is in the format contactForm[SOMETHING]
- value uses the value from the form (e.g. value="<?php $cform->printCurrentValue('SOMETHING') ?>")

### 4. Select Fields

Select fields need the options to be built out with php, use the following as an example.

_Replace SOMETHING with that fields name_

    <div>
      <label for="field_SOMETHING">SOMETHING LABEL:</label>
      <select name="contactForm[SOMETHING]" id="field_SOMETHING">
        <?php $cform->printOptionElements('SOMETHING', array('General', 'Website Help', 'Other')); ?>
      </select>
    </div>

### 5. Error Notices

There are 2 main points to be aware of with errors.

Display errors somewhere by adding the following somewhere on the page.

    <?php $cform->printErrorMessages() ?>

In the form element, use the printErrorClass to add an error class for targeting with CSS or JavaScript on a field if that field has an error. Note that something is that form files name.

    <input type="text" name="contactForm[SOMETHING]" class="<?php $cform->printErrorClass('SOMETHING') ?>"

### 6. Email

Any field that is stored as part of the FORMNAME[...] setup will be emailed,

e.g. if your form has the following fields with the given names...

- name="contactForm[name]"
- name="contactForm[email]"
- name="phone"

Then phone would not be emailed because it is not named properlly. The reason for this is to make it easy to control what fields you want to use for validation (e.g. the recaptcha data) and what data you want to let the contact form mail out.

### 7. Required fields

Inside the PHPContactor.setup.php is the required fields. There are currently 2 types. required and email. The default is to make email an email, and then make name and phone required. See the setup file for reference.
