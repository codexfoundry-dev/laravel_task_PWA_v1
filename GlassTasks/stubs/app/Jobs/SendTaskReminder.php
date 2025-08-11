<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\NotificationPreference;
use App\Notifications\TaskReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTaskReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Task $task) {}

    public function handle(): void
    {
        $task = $this->task->fresh();
        if (!$task || !$task->assignee) return;

        $prefs = NotificationPreference::firstOrCreate(['user_id' => $task->assignee_id]);

        if ($prefs->in_app) {
            $task->assignee->notify(new TaskReminderNotification($task, 'in_app'));
        }
        if ($prefs->email) {
            $task->assignee->notify(new TaskReminderNotification($task, 'email'));
        }
        if ($prefs->push) {
            $task->assignee->notify(new TaskReminderNotification($task, 'push'));
        }
    }
}