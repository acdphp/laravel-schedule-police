<?php

use Acdphp\SchedulePolice\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class)->in( 'Unit');
uses(TestCase::class, RefreshDatabase::class)->in( 'Feature');
