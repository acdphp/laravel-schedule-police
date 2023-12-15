<?php

namespace Acdphp\ScheduleControl\Data;

use Acdphp\ScheduleControl\Models\StoppedScheduledEvent;
use Illuminate\Console\Scheduling\Event;

class ScheduledTask
{
    public function __construct(
        public string $key,
        public Event $event,
        public ?StoppedScheduledEvent $stoppedEvent = null,
    ) {
    }

    public function isEventConsoleCommand(): bool
    {
        return preg_match("/^'.*php.*' 'artisan' /", $this->event->command);
    }
}
