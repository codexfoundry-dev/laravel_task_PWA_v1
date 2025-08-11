<?php

namespace App\Jobs;

use App\Models\Task;
use App\Services\RecurrenceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\CarbonImmutable;

class GenerateRecurringInstances implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle(RecurrenceService $recurrenceService): void
    {
        Task::query()
            ->where('is_recurring', true)
            ->whereNotNull('recurrence_rule')
            ->get()
            ->each(function (Task $task) use ($recurrenceService) {
                $after = CarbonImmutable::now();
                $next = $recurrenceService->nextOccurrence($task->recurrence_rule, $after);
                if ($next) {
                    Task::create([
                        'project_id' => $task->project_id,
                        'title' => $task->title,
                        'description' => $task->description,
                        'status' => 'todo',
                        'priority' => $task->priority,
                        'due_at' => $next,
                        'assignee_id' => $task->assignee_id,
                        'tags' => $task->tags,
                        'reminders' => $task->reminders,
                        'is_recurring' => $task->is_recurring,
                        'recurrence_rule' => $task->recurrence_rule,
                    ]);
                }
            });
    }
}