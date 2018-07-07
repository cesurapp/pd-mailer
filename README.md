# pdMailer Bundle
pdMailer is the SwiftMailer extension is written for pdAdmin. It keeps logs of mail sent by Swiftmailer and provides template interface for mail.

[![Packagist](https://img.shields.io/packagist/dt/rmznpydn/pd-mailer.svg)](https://github.com/rmznpydn/pd-mailer)
[![Github Release](https://img.shields.io/github/release/rmznpydn/pd-mailer.svg)](https://github.com/rmznpydn/pd-mailer)
[![license](https://img.shields.io/github/license/rmznpydn/pd-mailer.svg)](https://github.com/rmznpydn/pd-mailer)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/rmznpydn/pd-mailer.svg)](https://github.com/rmznpydn/pd-mailer)

Installation
---

### Step 1: Download the Bundle

This package is written for pdadmin and is required for installation.

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require rmznpydn/pd-mailer
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

With Symfony 4, the package will be activated automatically. But if something goes wrong, you can install it manually.

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
<?php
// config/bundles.php

return [
    //...
    Pd\MailerBundle\PdMailerBundle::class => ['all' => true]
];
```

Configs
---
create the config/packages/mailer.yaml file for the settings.
```yaml
logger_active: true
template_active: true
sender_address: 'example@example.com'
sender_name: 'pdMailer'
list_count: 30
active_language: ['tr', 'en']
```
* __logger_active__: Enable mail logs.
* __template_active__: Enable mail template.
* __sender_address__: Sender mail adress
* __sender_name__: Sender Name
* __list_count__: Log or Template page listing count
* __active_language__: List of active languages

How to use
---
The PDMailer plug-in will enable all mail to be logged by default. You must use the PdSwiftMessage class to add a template to the post.
```php
<?php

// Create Message
$message = (new PdSwiftMessage)
    ->setTemplateId('register_form_template') // Required
    ->setFrom('example@example.com', 'pdMailer')
    ->setTo('example@gmail.com')
    ->setSubject('Subject')
    ->setBody(serialize([
        'firstname' => 'Ramazan',
        'lastname' => 'Apaydın'
    ]), 'text/html'); // Data to be used in the template. - Required

// Send Mail
$this->get('mailer')->send($message);
```
Create a template for 'register_form_template' from the pdAdmin panel.
