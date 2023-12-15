<?php

namespace Acdphp\SchedulePolice\Tests\Feature\Services;

use Acdphp\SchedulePolice\Data\ScheduledEvent;
use Acdphp\SchedulePolice\Models\StoppedScheduledEvent;
use Acdphp\SchedulePolice\Services\SchedulePoliceService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    app()->make(Kernel::class);
    $schedule = app()->make(Schedule::class);

    $schedule->command('inspire')->everyMinute();
});

it('returns scheduled events', function () {
    expect(app(SchedulePoliceService::class)->getScheduledEvents())
        ->toHaveCount(1)
        ->toContainOnlyInstancesOf(ScheduledEvent::class)
        ->{0}->key->toBe('inspire')
        ->{0}->event->expression->toBe('* * * * *')
        ->{0}->stoppedEvent->toBeNull();
});

it('returns stopped scheduled events', function () {
    app(SchedulePoliceService::class)->stopSchedule('inspire', '* * * * *');

    expect(app(SchedulePoliceService::class)->getScheduledEvents())
        ->toHaveCount(1)
        ->toContainOnlyInstancesOf(ScheduledEvent::class)
        ->{0}->key->toBe('inspire')
        ->{0}->event->expression->toBe('* * * * *')
        ->{0}->stoppedEvent->key->toBe('inspire')
        ->{0}->stoppedEvent->expression->toBe('* * * * *');
});

it('can stop scheduled event', function () {
    app(SchedulePoliceService::class)->stopSchedule('inspire', '* * * * *');

    $this->assertDatabaseHas('stopped_scheduled_events', [
        'key' => 'inspire',
        'expression' => '* * * * *',
    ]);
});

it('can start scheduled event', function () {
    StoppedScheduledEvent::create(['key' => 'inspire', 'expression' => '* * * * *']);
    app(SchedulePoliceService::class)->startSchedule('inspire', '* * * * *');

    $this->assertDatabaseMissing('stopped_scheduled_events', [
        'key' => 'inspire',
        'expression' => '* * * * *',
    ]);
});

it('can execute command', function () {
    $mock = \Mockery::mock();

    $mock->shouldReceive('call')
        ->once()
        ->with('inspire')
        ->andReturn(1);

    $mock->shouldReceive('output')
        ->once()
        ->andReturn('Output');

    Artisan::swap($mock);

    app(SchedulePoliceService::class)->execCommand('inspire');
});

it('cannot execute blacklisted command', function () {
    Config::set('schedule-police.blacklisted_commands', [
        'inspire'
    ]);

    $mock = \Mockery::mock();

    $mock->shouldReceive('call')->never();

    $mock->shouldReceive('output')->never();

    Artisan::swap($mock);

    app(SchedulePoliceService::class)->execCommand('inspire');
});
