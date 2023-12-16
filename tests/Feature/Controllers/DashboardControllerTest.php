<?php

use Acdphp\SchedulePolice\Data\ExecResult;
use Acdphp\SchedulePolice\Http\Middleware\RestrictedAccess;
use Acdphp\SchedulePolice\Services\SchedulePoliceService;

it('has an index page', function () {
    $this->withoutMiddleware([RestrictedAccess::class]);

    $this->mock(SchedulePoliceService::class, function ($service) {
        $service->shouldReceive('isConfigured')
            ->once()
            ->andReturn(true);

        $service->shouldReceive('getScheduledEvents')
            ->once();
    });

    $this->get(route('schedule-police.index'))
        ->assertOk();
});

it('cannot access index page on non local', function () {
    $this->get(route('schedule-police.index'))
        ->assertForbidden();
});

it('can stop scheduled event', function () {
    $this->withoutMiddleware([RestrictedAccess::class]);

    $this->mock(SchedulePoliceService::class, function ($service) {
        $service->shouldReceive('stopSchedule')
            ->once()
            ->with('inspire', '* * * * *');
    });

    $this->post(route('schedule-police.stop'), [
        'key' => 'inspire',
        'expression' => '* * * * *',
    ])
        ->assertRedirectToRoute('schedule-police.index');
});

it('can start scheduled event', function () {
    $this->withoutMiddleware([RestrictedAccess::class]);

    $this->mock(SchedulePoliceService::class, function ($service) {
        $service->shouldReceive('startSchedule')
            ->once()
            ->with('inspire', '* * * * *');
    });

    $this->post(route('schedule-police.start'), [
        'key' => 'inspire',
        'expression' => '* * * * *',
    ])
        ->assertRedirectToRoute('schedule-police.index');
});

it('can execute commands', function () {
    $this->withoutMiddleware([RestrictedAccess::class]);

    $this->mock(SchedulePoliceService::class, function ($service) {
        $service->shouldReceive('execCommand')
            ->once()
            ->with('inspire')
            ->andReturn(new ExecResult('Message', false));
    });

    $this->post(route('schedule-police.exec'), [
        'command' => 'inspire',
    ])
        ->assertRedirect(route('schedule-police.index').'#v-execute');
});
