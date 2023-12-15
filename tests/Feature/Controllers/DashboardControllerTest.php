<?php

use Acdphp\ScheduleControl\Http\Middleware\RestrictedAccess;

it('has an index page', function () {
    $this->withoutMiddleware([RestrictedAccess::class]);

    $this->get(route('schedule-control.index'))
        ->assertOk();
});

it('cannot access index page on non local', function () {
    $this->get(route('schedule-control.index'))
        ->assertForbidden();
});

it('can stop scheduled event', function () {
    $this->withoutMiddleware([RestrictedAccess::class]);

    $this->post(route('schedule-control.stop'), [
            'key' => 'inspire',
        ])
        ->assertRedirectToRoute('schedule-control.index');

    $this->assertDatabaseHas('stopped_scheduled_events', [
        'key' => 'inspire',
    ]);
});
