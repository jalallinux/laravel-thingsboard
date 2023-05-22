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
        $this->registerFacades();
    }

    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/thingsboard.php' => config_path('thingsboard.php'),
            ]);
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

    public function registerFacades(): void
    {
        /** Register Helper function class */
        $this->app->singleton(Thingsboard::class, Thingsboard::class);

        /** Register RequestPaginationParams */
        $this->app->bind(config('thingsboard.container.namespace').'.PaginationParams');
    }
}
