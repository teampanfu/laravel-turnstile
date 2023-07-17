# Turnstile for Laravel

[![Latest Version](https://img.shields.io/github/release/teampanfu/laravel-turnstile.svg?style=flat-square)](https://github.com/teampanfu/laravel-turnstile/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/teampanfu/laravel-turnstile.svg?style=flat-square)](https://packagist.org/packages/teampanfu/laravel-turnstile)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

A package specifically designed to bring [Cloudflare's Turnstile](https://developers.cloudflare.com/turnstile) service directly into [Laravel](https://laravel.com).

## Installation

To install, use [Composer](https://getcomposer.org):

```sh
composer require teampanfu/laravel-turnstile
```

## Manual setup

As of Laravel 5.5, packages are automatically detected by [package discovery](https://laravel.com/docs/9.x/packages#package-discovery). So if you are using a newer version, you can skip these steps.

Add the following to your `config/app.php`:

```php
'providers' => [
    ...

    /*
     * Package Service Providers...
     */

    Panfu\Laravel\Turnstile\TurnstileServiceProvider::class,

    ...
],

'aliases' => [
    ...

    'Turnstile' => Panfu\Laravel\Turnstile\Facades\Turnstile::class,

    ...
],
```

Then publish the configuration file:

```php
php artisan vendor:publish --provider="Panfu\Laravel\Turnstile\TurnstileServiceProvider"
```

## Configuration

To get started, log in to the [Cloudflare Dashboard](https://dash.cloudflare.com), go to "Turnstile" in the side navigation, and add your website.

You will be given a site key and a secret key. You can add both to your `.env` file as follows:

```
TURNSTILE_SITEKEY=1x00000000000000000000AA
TURNSTILE_SECRET=1x0000000000000000000000000000000AA
```

## Usage

### Display

To display the widget on your website, you can simply add the following HTML code (assuming you are using [Blade](https://laravel.com/docs/blade)):

```html
<div class="cf-turnstile" data-sitekey="{{ config('turnstile.sitekey') }}"></div>
```

*A list of [available configurations](https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/#configurations) (such as theme or language) can be found in the documentation.*

### Script

In order for the widget to work, you need to include the JavaScript resource. You can add the following HTML code for this:

```html
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
```

### Validation

To validate a request, you can use the following rule:

```php
$request->validate([
    'cf-turnstile-response' => ['turnstile'],
]);
```

*You can leave out the `required` rule, it is already handled by this package.*

#### Custom validation message

You may want to display an error message in case the validation fails. Add the following to your `lang/[lang]/validation.php` file:

```php
'custom' => [
    'cf-turnstile-response' => [
        'turnstile' => 'The validation has failed.',
    ],
],
```

### Use without Laravel

Although the package is specifically designed for Laravel, it can still be used without it. Here is an example of how it works:

```php
<?php

require_once 'vendor/autoload.php';

use Panfu\Laravel\Turnstile\Turnstile;

$sitekey = '1x00000000000000000000AA';
$secret = '1x0000000000000000000000000000000AA';
$turnstile = new Turnstile($secret);

if (! empty($_POST)) {
    $responseToken = $_POST['cf-turnstile-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];

    $isValid = $turnstile->validate($responseToken, $remoteip); // $remoteip is optional

    if ($isValid) {
        echo 'Token is valid.';
    } else {
        echo 'Token is not valid.';
    }
    exit;
}

?>

<form method="POST">
    <div class="cf-turnstile" data-sitekey="<?= $sitekey ?>"></div>
    <button type="submit">Submit</button>
</form>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
```

*Remote IP helps prevent abuses by ensuring that the current visitor is the one who received the token.*

### Testing

```sh
$ ./vendor/bin/phpunit
```

### Contribute

If you find a bug or have a feature suggestion, feel free to create a new issue or pull request.

We appreciate every contribution!

### License

This package is open-source software licensed under the [MIT License](LICENSE).
