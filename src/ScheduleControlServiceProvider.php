<?php

namespace Acdphp\ScheduleControl;

use Illuminate\Support\ServiceProvider;

class ScheduleControlServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Config merge
        $this->mergeConfigFrom(__DIR__ . '/../config/schedule-control.php', 'schedule-control');
    }

    public function boot(): void
    {
        $this->load();
        $this->publish();
    }

    private function load(): void
    {
        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'schedule-control');

        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    private function publish(): void
    {
        // Config
        $this->publishes([
            __DIR__ . '/../config/schedule-control.php' => config_path('schedule-control.php'),
        ], 'schedule-control-config');

        // Assets
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/schedule-control'),
        ], 'schedule-control-assets');
    }
}
