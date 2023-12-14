<?php

namespace Acdphp\ScheduleControl\Dtos;

class ExecResultDto
{
    public function __construct(
        public string $message,
        public int $isError,
    ) {
    }
}
