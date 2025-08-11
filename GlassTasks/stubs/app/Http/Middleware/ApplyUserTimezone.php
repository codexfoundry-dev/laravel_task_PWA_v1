<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use DateTimeZone;

class ApplyUserTimezone
{
    public function handle(Request $request, Closure $next)
    {
        $timezone = $request->user()->timezone ?? config('app.timezone', 'Europe/London');
        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);
        CarbonImmutable::setTestNow();
        return $next($request);
    }
}