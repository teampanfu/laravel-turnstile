<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site Key
    |--------------------------------------------------------------------------
    |
    | Unique identifier for your website in Cloudflare's Turnstile service.
    |
    */

    'sitekey' => env('TURNSTILE_SITEKEY', '1x00000000000000000000AA'),

    /*
    |--------------------------------------------------------------------------
    | Secret Key
    |--------------------------------------------------------------------------
    |
    | Cryptographic key for your website in Cloudflare's Turnstile service.
    | Keep this secret and don't share it publicly.
    |
    */

    'secret' => env('TURNSTILE_SECRET', '1x0000000000000000000000000000000AA'),

];
