<?php

namespace Acdphp\ScheduleControl;

use Illuminate\Support\ServiceProvider;

class ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Config merge
        $this->mergeConfigFrom(__DIR__ . '/../config/schedule-control.php', 'schedule-control');
    }

    public function boot(): void
    {
        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'schedule-control');

        // Publishes the configuration file
        $this->publishes([
            __DIR__ . '/../config/schedule-control.php' => config_path('schedule-control.php'),
        ], 'schedule-control-config');

        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
