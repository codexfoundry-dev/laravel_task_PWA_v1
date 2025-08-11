<?php

use App\Services\RecurrenceService;
use Carbon\CarbonImmutable;

it('expands weekly rrule to next', function () {
    $service = app(RecurrenceService::class);
    $next = $service->nextOccurrence('FREQ=WEEKLY;INTERVAL=1', CarbonImmutable::parse('2024-01-01'));
    expect($next->toDateString())->toBe('2024-01-08');
});