<?php

namespace Acdphp\SchedulePolice\Console\Scheduling;

use Acdphp\SchedulePolice\Services\SchedulePoliceService;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule as IlluminateSchedule;

class Schedule extends IlluminateSchedule
{
    /**
     * {@inheritDoc}
     */
    public function dueEvents($app)
    {
        /**
         * @var SchedulePoliceService $service
         */
        $service = app(SchedulePoliceService::class);

        return parent::dueEvents($app)->filter(function (Event $event) use ($service) {
            return ! $service->stoppedEvent($event);
        });
    }
}
