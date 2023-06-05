<?php

namespace JalalLinuX\Thingsboard;

use Illuminate\Support\ServiceProvider;

class LaravelThingsboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/thingsboard.php', 'thingsboard');
    }

    public function boot(): void
    {
        $this->registerCommands();
        $this->registerPublishing();
        $this->registerBindings();
        $this->registerTranslations();
    }

    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/thingsboard.php' => config_path('thingsboard.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../lang' => lang_path('vendor/laravel-thingsboard'),
            ], 'lang');
        }
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                //
            ]);
        }
    }

    public function registerBindings(): void
    {
        /** Register Helper function class */
        $this->app->singleton(Thingsboard::class);
    }

    public function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'thingsboard');
    }
}
