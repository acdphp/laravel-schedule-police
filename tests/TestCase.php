<?php

namespace Acdphp\ScheduleControl\Tests;

use Acdphp\ScheduleControl\ScheduleControlServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ScheduleControlServiceProvider::class,
        ];
    }
}
