<?php

namespace Acdphp\ScheduleControl\Console\Scheduling;

use Acdphp\ScheduleControl\Services\ScheduleControlService;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule as IlluminateSchedule;

class Schedule extends IlluminateSchedule
{
    /**
     * {@inheritDoc}
     */
    public function dueEvents($app)
    {
        $service = app(ScheduleControlService::class);

        return parent::dueEvents($app)->filter(function (Event $event) use ($service) {
            return ! $service->eventStoppedAt($event);
        });
    }
}
