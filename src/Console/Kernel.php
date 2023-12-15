<?php

namespace Acdphp\SchedulePolice\Console;

use Acdphp\SchedulePolice\Console\Scheduling\Schedule as ControlledSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function defineConsoleSchedule()
    {
        $this->app->singleton(Schedule::class, function () {
            return tap(new ControlledSchedule($this->scheduleTimezone()), function ($schedule) {
                $this->schedule($schedule->useCache($this->scheduleCache()));
            });
        });
    }
}
