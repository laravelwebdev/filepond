<?php

declare(strict_types = 1);

namespace Laravelwebdev\Filepond;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/filepond.php' => config_path('filepond.php'),
        ], 'config');

        $this->app->booted(function (): void {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::mix('filepond', __DIR__.'/../dist/mix-manifest.json');
        });
    }

    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware([ 'nova' ])
            ->prefix('nova-components/filepond')
            ->group(__DIR__ . '/../routes/api.php');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filepond.php', 'filepond');
    }
}
