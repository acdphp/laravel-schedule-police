<?php

namespace Acdphp\SchedulePolice\Tests\Feature\Services;

use Acdphp\SchedulePolice\Models\StoppedScheduledEvent;
use Acdphp\SchedulePolice\Services\SchedulePoliceService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    app()->make(Kernel::class);
    $this->schedule = app()->make(Schedule::class);

    $this->schedule->command('inspire')->everyMinute();

    $this->service = app(SchedulePoliceService::class);
});

it('returns scheduled events', function () {
    expect($this->service->getScheduledEvents())
        ->toHaveCount(1)
        ->{0}->key->toBe('inspire')
        ->{0}->event->expression->toBe('* * * * *')
        ->{0}->stodwxppedEvent->toBeNull();
});

it('returns stopped scheduled events', function () {
    $this->service->stopSchedule('inspire', '* * * * *');

    expect($this->service->getScheduledEvents())
        ->toHaveCount(1)
        ->{0}->key->toBe('inspire')
        ->{0}->event->expression->toBe('* * * * *')
        ->{0}->stoppedEvent->key->toBe('inspire')
        ->{0}->stoppedEvent->expression->toBe('* * * * *');
});

it('can stop scheduled event', function () {
    $this->service->stopSchedule('inspire', '* * * * *');

    $this->assertDatabaseHas('stopped_scheduled_events', [
        'key' => 'inspire',
        'expression' => '* * * * *',
    ]);
});

it('can start scheduled event', function () {
    StoppedScheduledEvent::create(['key' => 'inspire', 'expression' => '* * * * *']);
    $this->service->startSchedule('inspire', '* * * * *');

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

    $this->service->execCommand('inspire');
});

it('cannot execute blacklisted command', function () {
    Config::set('schedule-police.blacklisted_commands', [
        'inspire',
    ]);

    $this->service->setConfig(config('schedule-police'));

    $mock = \Mockery::mock();

    $mock->shouldReceive('call')->never();

    $mock->shouldReceive('output')->never();

    Artisan::swap($mock);

    $this->service->execCommand('inspire');
});
