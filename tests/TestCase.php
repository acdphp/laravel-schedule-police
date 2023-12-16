<?php

namespace Acdphp\SchedulePolice\Tests;

use Acdphp\SchedulePolice\SchedulePoliceServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            SchedulePoliceServiceProvider::class,
        ];
    }
}
