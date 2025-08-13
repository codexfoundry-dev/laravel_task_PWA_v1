<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\NotificationPreference;
use App\Notifications\DailyDigestNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\CarbonImmutable;

class SendDailyDigest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $nowHour = CarbonImmutable::now('Europe/London')->hour;
        $users = User::query()->get();
        foreach ($users as $user) {
            $prefs = NotificationPreference::firstOrCreate(['user_id' => $user->id]);
            if ($prefs->daily_digest_hour === $nowHour) {
                $user->notify(new DailyDigestNotification($user));
            }
        }
    }
}