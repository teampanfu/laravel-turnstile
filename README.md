# Turnstile for Laravel

[![Latest Version](https://img.shields.io/github/release/teampanfu/laravel-turnstile.svg?style=flat-square)](https://github.com/teampanfu/laravel-turnstile/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/teampanfu/laravel-turnstile.svg?style=flat-square)](https://packagist.org/packages/teampanfu/laravel-turnstile)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

A Laravel package that integrates [Cloudflare's Turnstile](https://developers.cloudflare.com/turnstile) CAPTCHA service.

## Requirements

For Laravel integration:
- PHP 8.2 or higher
- [Laravel](https://laravel.com) 9 or higher

For standalone usage:
- PHP 8.2 or higher
- [Guzzle HTTP](https://docs.guzzlephp.org) 7.8 or higher

## Installation

```sh
composer require teampanfu/laravel-turnstile
```

## Laravel Integration

1. Add to your `.env`:

```
TURNSTILE_SITEKEY=1x00000000000000000000AA
TURNSTILE_SECRET=1x0000000000000000000000000000000AA
```

### Add the Widget

```blade
<form method="POST">
    @csrf
    <div class="cf-turnstile" data-sitekey="{{ config('turnstile.sitekey') }}"></div>
    <button type="submit">Submit</button>
</form>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
```

See [available configurations](https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/#configurations) for theme, language, etc.

### Validate the Response

```php
$request->validate([
    'cf-turnstile-response' => ['required', 'turnstile'],
]);
```

### Custom Error Message

In `lang/[lang]/validation.php`:

```php
'custom' => [
    'cf-turnstile-response' => [
        'turnstile' => 'Please verify that you are human.',
    ],
],
```

## Standalone Usage

The package can also be used without Laravel:

```php
<?php

use Panfu\Laravel\Turnstile\Turnstile;

$turnstile = new Turnstile('your-secret-key');

try {
    if ($turnstile->validate($_POST['cf-turnstile-response'])) {
        // Verification successful
    }
} catch (\RuntimeException $e) {
    // Handle validation error (e.g., invalid-input-response)
} catch (\GuzzleHttp\Exception\GuzzleException $e) {
    // Handle network/request error
}
```

## Testing

```sh
./vendor/bin/phpunit
```

## License

[MIT License](LICENSE)
