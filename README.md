<p align="center"><img width="200" src="https://image.flaticon.com/icons/svg/1982/1982945.svg" alt="Laravel Email Failer" /></p>

[![Build Status](https://travis-ci.org/rogervila/laravel-email-failer.svg?branch=master)](https://travis-ci.org/rogervila/laravel-email-failer)
[![Build status](https://ci.appveyor.com/api/projects/status/4jvwpqfea2x9h95j/branch/master?svg=true)](https://ci.appveyor.com/project/roger-vila/laravel-email-failer/branch/master)
[![StyleCI](https://github.styleci.io/repos/195772522/shield?branch=master)](https://github.styleci.io/repos/195772522)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=rogervila_laravel-email-failer&metric=alert_status)](https://sonarcloud.io/dashboard?id=rogervila_laravel-email-failer)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=rogervila_laravel-email-failer&metric=coverage)](https://sonarcloud.io/dashboard?id=rogervila_laravel-email-failer)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=rogervila_laravel-email-failer&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=rogervila_laravel-email-failer)
[![Latest Stable Version](https://poser.pugx.org/rogervila/laravel-email-failer/v/stable)](https://packagist.org/packages/rogervila/laravel-email-failer)
[![Total Downloads](https://poser.pugx.org/rogervila/laravel-email-failer/downloads)](https://packagist.org/packages/rogervila/laravel-email-failer)
[![License](https://poser.pugx.org/rogervila/laravel-email-failer/license)](https://packagist.org/packages/rogervila/laravel-email-failer)
[![MadeWithLaravel.com shield](https://madewithlaravel.com/storage/repo-shields/2217-shield.svg)](https://madewithlaravel.com/p/laravel-email-failer/shield-link)

# Laravel Email Failer

```sh
composer require --dev rogervila/laravel-email-failer
```

## About

Trigger email failures to assert what happens on your Laravel Application when an email fails to send

## Usage

Once the `mailer` instance is replaced, all emails will fail. This helps to assert that your application Mail exceptions are handled correctly (ie: mark the email address as invalid)

```php
public function test_happy_path()
{
    Mail::fake();

    MyService::sendEmail();

    Mail::assertSent(MyMailable::class);
}

public function test_email_failures()
{
    $mailer = new \LaravelEmailFailer\MailFailer;
    $this->app->instance('mailer', $mailer);
    
    MyService::sendEmail();

    Mail::assertNotSent(MyMailable::class);
    
    dump(Mail::failures());
    // Assert here what happens when the email has failed
}

```


## License

Laravel Email Failer is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


Icon made by <a href="https://www.flaticon.com/authors/darius-dan" title="Darius Dan">Darius Dan</a> from <a href="https://www.flaticon.com/"    title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/"                 title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a>
