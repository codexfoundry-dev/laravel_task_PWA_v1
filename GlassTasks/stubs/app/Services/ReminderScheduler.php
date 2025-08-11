<?php

namespace App\Services;

use App\Models\Task;
use App\Jobs\SendTaskReminder;
use Carbon\CarbonImmutable;

class ReminderScheduler
{
    public function schedule(Task $task, bool $replaceExisting = false): void
    {
        if ($replaceExisting) {
            // Here you could clear existing pending reminder jobs for this task via tags.
            // Left as a note; implement if using a job repository/store.
        }
        if (!$task->due_at || empty($task->reminders)) {
            return;
        }
        $due = CarbonImmutable::parse($task->due_at);
        foreach ($task->reminders as $offset) {
            $runAt = $this->calculateRunAt($due, $offset);
            if ($runAt) {
                SendTaskReminder::dispatch($task)->delay($runAt);
            }
        }
    }

    private function calculateRunAt(CarbonImmutable $due, string $offset): ?CarbonImmutable
    {
        // offsets like 1d, 2h, 15m
        if (!preg_match('/^(\d+)([dhm])$/', trim($offset), $m)) {
            return null;
        }
        $value = (int) $m[1];
        $unit = $m[2];
        return match ($unit) {
            'd' => $due->subDays($value),
            'h' => $due->subHours($value),
            'm' => $due->subMinutes($value),
            default => null,
        };
    }
}