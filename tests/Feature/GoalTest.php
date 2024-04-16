<?php

use Acdphp\SchedulePolice\Console\Scheduling\Schedule;
use Acdphp\SchedulePolice\Services\SchedulePoliceService;
use Acdphp\SchedulePolice\Tests\Dummy\Command\Test;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;

beforeEach(function () {
    $this->schedule = app()->make(Schedule::class);
    $this->service = app(SchedulePoliceService::class);

    $this->schedule->command('inspire')->everyMinute();
    $this->schedule->command(Test::class)->everyMinute();
    $this->schedule->command(Test::class)->hourly();

    Date::setTestNow(Carbon::create(2000, 1, 1, 1, 0));

    $scheduleEvents = $this->schedule->dueEvents(app());
    expect($scheduleEvents)->toHaveCount(3);
});

test('will not run stopped event of the same command', function () {
    Config::set('schedule-police.separate_by_frequency', false);

    $this->service->stopSchedule('app:test', '* * * * *');

    $scheduleEvents = $this->schedule->dueEvents(app());
    expect($scheduleEvents)->toHaveCount(1);

    $removedFromSchedule = $scheduleEvents->contains(function (Event $event) {
        return
            ($this->service->getEventkey($event) === 'app:test' && $event->expression === '* * * * *') ||
            ($this->service->getEventkey($event) === 'app:test' && $event->expression === '0 * * * *');
    });

    expect($removedFromSchedule)->toBeFalse();
});

test('will not run stopped event of the same command and expression', function () {
    Config::set('schedule-police.separate_by_frequency', true);

    $this->service->stopSchedule('app:test', '* * * * *');

    $scheduleEvents = $this->schedule->dueEvents(app());
    expect($scheduleEvents)->toHaveCount(2);

    $removedFromSchedule = $scheduleEvents->contains(function (Event $event) {
        return $this->service->getEventkey($event) === 'app:test' && $event->expression === '* * * * *';
    });

    expect($removedFromSchedule)->toBeFalse();
});
