<?php

use Acdphp\SchedulePolice\Http\Controllers\DashboardController;
use Acdphp\SchedulePolice\Http\Middleware\RestrictedAccess;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

Route::prefix(config('schedule-police.url_prefix').'/schedule-police')
    ->middleware(config('schedule-police.middleware', [StartSession::class, RestrictedAccess::class]))
    ->controller(DashboardController::class)
    ->group(function () {
        Route::get('/', 'index')->name('schedule-police.index');
        Route::post('/stop', 'stop')->name('schedule-police.stop');
        Route::post('/start', 'start')->name('schedule-police.start');
        Route::post('/exec', 'exec')->name('schedule-police.exec');
    });
