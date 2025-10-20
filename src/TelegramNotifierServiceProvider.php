<?php

namespace Voronyuk\TelegramNotifier;

use Illuminate\Support\ServiceProvider;
use Voronyuk\TelegramNotifier\Services\TelegramNotifierFactory;

class TelegramNotifierServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge package config with application config
        $this->mergeConfigFrom(
            __DIR__.'/../config/telegram-notifier.php',
            'telegram-notifier'
        );

        // Register the main factory
        $this->app->singleton('telegram.notifier', function ($app) {
            return new TelegramNotifierFactory();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/telegram-notifier.php' => config_path('telegram-notifier.php'),
            ], 'telegram-notifier-config');
        }
    }
}