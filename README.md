# pdMailer Bundle
pdMailer is the Symfony Mailer extension is written for pdAdmin. It keeps logs of mail sent by Symfony Mailer and provides template interface for mail.

[![Packagist](https://img.shields.io/packagist/dt/appaydin/pd-mailer.svg)](https://github.com/appaydin/pd-mailer)
[![Github Release](https://img.shields.io/github/release/appaydin/pd-mailer.svg)](https://github.com/appaydin/pd-mailer)
[![license](https://img.shields.io/github/license/appaydin/pd-mailer.svg)](https://github.com/appaydin/pd-mailer)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/appaydin/pd-mailer.svg)](https://github.com/appaydin/pd-mailer)

Installation
---

### Step 1: Download the Bundle

This package is written for pdadmin and is required for installation.

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require appaydin/pd-mailer
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

With Symfony 5, the package will be activated automatically. But if something goes wrong, you can install it manually.

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
list_count: 30
active_language: ['tr', 'en']
```
* __logger_active__: Enable mail logs.
* __mail_template_type__: Mail template form type
* __template_active__: Enable mail template.
* __list_count__: Log or Template page listing count
* __active_language__: List of active languages
* __base_template__: Mail template form type
* __template_path__: Enable mail template.

How to use
---
The PDMailer plug-in will enable all mail to be logged by default.
```php
<?php

// Create Message
$email = new Email();
$email
    ->from('example@example.com')
    ->to('example@gmail.com')
    ->subject('Subject')
    ->html([
        'firstname' => 'Ramazan', 'lastname' => 'APAYDIN'
    ]) // Data to be used in the template. - Required
    ->getHeaders()->addTextHeader('template', 'customTemplateID'); // Required

// Send Mail
$this->get('mailer')->send($email);
```
Create a template for 'customTemplateID' from the pdAdmin panel.
