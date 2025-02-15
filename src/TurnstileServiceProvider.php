<?php

namespace Panfu\Laravel\Turnstile;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

final class TurnstileServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/turnstile.php' => config_path('turnstile.php'),
        ]);

        $this->app->validator->extend('turnstile', function ($attribute, $value) {
            try {
                return $this->app->turnstile->validate($value, request()->ip());
            } catch (\RuntimeException $e) {
                Log::warning('Turnstile validation failed:', [
                    'message' => $e->getMessage(),
                ]);

                return false;
            } catch (\GuzzleHttp\Exception\GuzzleException $e) {
                Log::error('Turnstile request failed:', [
                    'message' => $e->getMessage(),
                ]);

                return false;
            }
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/turnstile.php', 'turnstile');

        $this->app->singleton('turnstile', function ($app) {
            return new Turnstile(
                $app['config']->get('turnstile.secret')
            );
        });
    }
}
