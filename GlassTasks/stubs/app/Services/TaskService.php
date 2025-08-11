<?php

namespace App\Services;

use App\Models\Task;
use App\Jobs\SendTaskReminder;
use App\Services\ReminderScheduler;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Carbon\CarbonImmutable;

class TaskService
{
    public function __construct(private readonly ReminderScheduler $reminderScheduler) {}

    public function createWithReminders(array $data): Task
    {
        $task = Task::create($data);
        $this->scheduleReminders($task);
        return $task;
    }

    public function updateWithReminders(Task $task, array $data): Task
    {
        $task->fill($data);
        $task->save();
        $this->scheduleReminders($task, true);
        return $task;
    }

    public function quickAdd(string $input, int $projectId, ?int $assigneeId = null): Task
    {
        $parsed = $this->parseQuickAdd($input);
        $task = Task::create(array_merge($parsed, [
            'project_id' => $projectId,
            'assignee_id' => $assigneeId ?? ($parsed['assignee_id'] ?? null),
        ]));
        $this->scheduleReminders($task);
        return $task;
    }

    private function scheduleReminders(Task $task, bool $replace = false): void
    {
        $this->reminderScheduler->schedule($task, $replace);
    }

    public function parseQuickAdd(string $text): array
    {
        // Example: "Buy milk tomorrow 5pm #home @me !high"
        $title = $text;
        $priority = null;
        $tags = [];
        $dueAt = null;
        $assigneeId = null;

        // priority: !low|!med|!high
        if (preg_match('/!(low|med|high)/i', $text, $m)) {
            $priority = strtolower($m[1]);
            $title = trim(str_replace($m[0], '', $title));
        }
        // tags: #tag
        if (preg_match_all('/#(\w+)/', $text, $m)) {
            $tags = array_map('strtolower', $m[1]);
            $title = trim(str_replace($m[0], '', $title));
        }
        // assignee: @me (placeholder, real mapping in controller)
        if (preg_match('/@(\w+)/', $text, $m)) {
            if (strtolower($m[1]) === 'me') {
                $assigneeId = auth()->id();
            }
            $title = trim(str_replace($m[0], '', $title));
        }
        // due date: naive phrases today/tomorrow HHam/pm
        if (preg_match('/\b(today|tomorrow)\b(.*?\b(\d{1,2})(?::(\d{2}))?\s*(am|pm)?\b)?/i', $text, $m)) {
            $base = strtolower($m[1]) === 'tomorrow' ? CarbonImmutable::now()->addDay() : CarbonImmutable::now();
            $hour = isset($m[3]) ? (int) $m[3] : 9;
            $minute = isset($m[4]) ? (int) $m[4] : 0;
            $ampm = isset($m[5]) ? strtolower($m[5]) : null;
            if ($ampm === 'pm' && $hour < 12) $hour += 12;
            if ($ampm === 'am' && $hour === 12) $hour = 0;
            $dueAt = $base->setTime($hour, $minute);
            $title = trim(str_replace($m[0], '', $title));
        }

        return array_filter([
            'title' => trim($title),
            'priority' => $priority,
            'tags' => $tags ?: null,
            'due_at' => $dueAt?->toDateTimeString(),
            'assignee_id' => $assigneeId,
        ], fn ($v) => $v !== null && $v !== '');
    }
}