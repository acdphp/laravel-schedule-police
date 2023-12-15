<?php

use Acdphp\ScheduleControl\Http\Middleware\RestrictedAccess;

return [
    /*
    |--------------------------------------------------------------------------
    | URL prefix
    |--------------------------------------------------------------------------
    |
    | Use this when you need to add prefix to the routes.
    |
    */
    'url_prefix' => env('SCHEDULE_CONTROL_URL_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Separate control by frequency
    |--------------------------------------------------------------------------
    |
    | Determine if similar commands should be controlled separately when
    | having different frequency/expression. This will still show separate
    | entries in the view list but both will share the control.
    |
    */
    'separate_by_frequency' => false,

    /*
    |--------------------------------------------------------------------------
    | Enable execution
    |--------------------------------------------------------------------------
    |
    | Disable command execution from the dashboard.
    |
    */
    'enable_execution' => env('SCHEDULE_CONTROL_ALLOW_EXECUTE_CMD', true),

    /*
    |--------------------------------------------------------------------------
    | Blacklisted commands
    |--------------------------------------------------------------------------
    |
    | Disable execution of specific commands.
    | The commands specified will be the root of comparison, meaning all commands
    | that will be executed from the dashboard that starts with it will be prevented.
    |
    | E.g. 'migrate' will also block 'migrate:fresh', 'migrate --seed', etc.
    |
    */
    'blacklisted_commands' => [
        'migrate:fresh',
    ],


    /*
    |--------------------------------------------------------------------------
    | Causer key
    |--------------------------------------------------------------------------
    |
    | Blame.
    |
    */
    'causer_key' => 'email',

    /*
    |--------------------------------------------------------------------------
    | Sort tasks by stopped
    |--------------------------------------------------------------------------
    |
    | When true, stopped tasks will be sorted to be on top. This is default
    | to false since it could be confusing to see tasks flying around.
    |
    */
    'sort_by_stopped' => false,

    'middleware' => [
        'web',
        RestrictedAccess::class,
    ],
];
