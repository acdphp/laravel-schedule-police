<?php

namespace Acdphp\SchedulePolice\Data;

class ExecResult
{
    public function __construct(
        public string $message,
        public int $isError,
    ) {
    }
}
