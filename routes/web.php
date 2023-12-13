<?php

use Acdphp\ScheduleControl\Http\Controllers\DashboardController;
use Acdphp\ScheduleControl\Http\Middleware\RestrictedAccess;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

Route::prefix(config('schedule-control.url_prefix') . '/schedule-control')
    ->middleware(config('schedule-control.middleware', [StartSession::class, RestrictedAccess::class]))
    ->controller(DashboardController::class)
    ->group(function () {
        Route::get('/', 'index')->name('schedule-control.index');
        Route::post('/stop', 'stop')->name('schedule-control.stop');
        Route::post('/start', 'start')->name('schedule-control.start');
        Route::post('/exec', 'exec')->name('schedule-control.exec');
    });
