<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class CalendarController extends Controller
{
    public function feed(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');
        $tasks = Task::query()
            ->whereNotNull('due_at')
            ->when($start, fn($q) => $q->whereDate('due_at', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('due_at', '<=', $end))
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'title' => $t->title,
                'start' => optional($t->due_at)->toISOString(),
                'allDay' => false,
                'extendedProps' => [
                    'project_id' => $t->project_id,
                    'status' => $t->status,
                    'priority' => $t->priority,
                ],
            ]);
        return response()->json($tasks);
    }

    public function ics(Request $request)
    {
        $user = $request->user();
        $tasks = Task::query()->where('assignee_id', $user->id)->whereNotNull('due_at')->get();
        $calendar = Calendar::create('GlassTasks')
            ->productIdentifier('GlassTasks')
            ->refreshInterval(60);
        foreach ($tasks as $task) {
            $calendar->event(Event::create($task->title)
                ->startsAt($task->due_at)
                ->description($task->description ?? '')
            );
        }
        return response($calendar->get(), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="glasstasks.ics"',
        ]);
    }
}