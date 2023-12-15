<?php

namespace Acdphp\SchedulePolice;

use Illuminate\Support\ServiceProvider;

class SchedulePoliceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Config merge
        $this->mergeConfigFrom(__DIR__ . '/../config/schedule-police.php', 'schedule-police');
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'schedule-police');

        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    private function publish(): void
    {
        // Config
        $this->publishes([
            __DIR__ . '/../config/schedule-police.php' => config_path('schedule-police.php'),
        ], 'schedule-police-config');

        // Assets
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/schedule-police'),
        ], 'schedule-police-assets');
    }
}
