<?php

namespace Panfu\Laravel\Turnstile;

use Illuminate\Support\ServiceProvider;

class TurnstileServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        // Publish configuration file
        $this->publishes([
            __DIR__.'/../config/turnstile.php' => config_path('turnstile.php'),
        ]);

        // Extend the validator with the custom 'turnstile' rule
        $this->app->validator->extend('turnstile', function ($attribute, $value) {
            return $this->app->turnstile->validate($value, request()->ip());
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge the package's configuration with the application's configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/turnstile.php', 'turnstile'
        );

        // Bind 'turnstile' into the service container as a singleton
        $this->app->singleton('turnstile', function () {
            return new Turnstile(
                config('turnstile.secret'),
            );
        });
    }
}
