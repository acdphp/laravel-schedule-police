<?php

namespace Acdphp\ScheduleControl\Dtos;

use Illuminate\Support\Carbon;

class StoppedEventDto
{
    public function __construct(
        public string $key,
        public Carbon $at,
        public string $by,
    ) {
    }
}
