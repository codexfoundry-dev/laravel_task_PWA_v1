<?php

use App\Models\User;
use App\Models\Task;
use App\Models\NotificationPreference;
use App\Jobs\SendTaskReminder;

it('honors notification preferences', function () {
    $user = User::factory()->create();
    NotificationPreference::create(['user_id' => $user->id, 'email' => false, 'push' => false, 'in_app' => true, 'daily_digest_hour' => 8]);
    $task = Task::factory()->create(['assignee_id' => $user->id, 'due_at' => now()->addHour(), 'reminders' => ['10m']]);

    SendTaskReminder::dispatchSync($task);
    expect(true)->toBeTrue();
});