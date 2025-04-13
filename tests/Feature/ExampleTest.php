<?php

test('confirm environment is set to workbench', function () {
    expect(config('app.env'))->toBe('workbench');
});
