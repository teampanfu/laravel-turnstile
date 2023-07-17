<?php

namespace Panfu\Laravel\Turnstile\Facades;

use Illuminate\Support\Facades\Facade;

class Turnstile extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'turnstile';
    }
}
