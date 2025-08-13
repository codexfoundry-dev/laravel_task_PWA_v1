<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use RRule\RRule; // Use a RRULE library if installed; else simple fallback

class RecurrenceService
{
    public function nextOccurrence(string $rrule, CarbonImmutable $after): ?CarbonImmutable
    {
        // If you add a proper RRULE lib, parse it here. As a fallback, simple weekly.
        try {
            if (class_exists(RRule::class)) {
                $rule = new RRule($rrule, $after->toDateTime());
                $next = $rule->getOccurrencesAfter($after->toDateTime(), true, 1);
                if (!empty($next)) {
                    return CarbonImmutable::instance($next[0]);
                }
            }
        } catch (\Throwable $e) {
            // ignore and fallback
        }
        // fallback: weekly interval
        return $after->addWeek();
    }
}