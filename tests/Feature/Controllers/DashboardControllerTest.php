<?php

use Acdphp\SchedulePolice\Http\Middleware\RestrictedAccess;

it('has an index page', function () {
    $this->withoutMiddleware([RestrictedAccess::class]);

    $this->get(route('schedule-police.index'))
        ->assertOk();
});

it('cannot access index page on non local', function () {
    $this->get(route('schedule-police.index'))
        ->assertForbidden();
});

it('can stop scheduled event', function () {
    $this->withoutMiddleware([RestrictedAccess::class]);

    $this->post(route('schedule-police.stop'), [
            'key' => 'inspire',
        ])
        ->assertRedirectToRoute('schedule-police.index');

    $this->assertDatabaseHas('stopped_scheduled_events', [
        'key' => 'inspire',
    ]);
});
