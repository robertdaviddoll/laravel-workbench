<?php

namespace RobertDavidDoll\LaravelWorkbench\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class LaravelWorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/workbench.php', 'workbench'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->authorization();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/workbench.php' => config_path('workbench.php'),
            ], 'workbench-config');

            $this->publishes([
                __DIR__.'/../../public' => public_path('vendor/workbench'),
            ], 'workbench-assets');
        }

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'workbench');
        
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Register the package's default authorization gate.
     */
    protected function authorization(): void
    {
        Gate::define('viewWorkbench', function ($user = null) {
            return app()->environment('local');
        });
    }
}
