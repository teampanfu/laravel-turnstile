<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Turnstile Keys
    |--------------------------------------------------------------------------
    |
    | These are the credentials needed to use Cloudflare's Turnstile service.
    | You can find these in your Cloudflare dashboard.
    |
    */

    'sitekey' => env('TURNSTILE_SITEKEY', '1x00000000000000000000AA'),
    'secret' => env('TURNSTILE_SECRET', '1x0000000000000000000000000000000AA'),

];
