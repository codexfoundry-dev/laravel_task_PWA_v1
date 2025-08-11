<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Events\TaskMoved;
use App\Events\TaskUpdated;
use App\Events\TaskCompleted;

class TasksController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    public function index(Request $request)
    {
        $query = Task::query()->with(['project', 'assignee']);
        if ($projectId = $request->query('project_id')) $query->where('project_id', $projectId);
        if ($status = $request->query('status')) $query->where('status', $status);
        if ($priority = $request->query('priority')) $query->where('priority', $priority);
        if ($assigneeId = $request->query('assignee_id')) $query->where('assignee_id', $assigneeId);
        if ($tag = $request->query('tag')) $query->whereJsonContains('tags', $tag);
        if ($from = $request->query('from')) $query->whereDate('due_at', '>=', $from);
        if ($to = $request->query('to')) $query->whereDate('due_at', '<=', $to);
        $tasks = $query->paginate(30);
        return response()->json(['data' => $tasks]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:todo,doing,done'],
            'priority' => ['required', 'in:low,med,high'],
            'due_at' => ['nullable', 'date'],
            'start_at' => ['nullable', 'date'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'tags' => ['nullable', 'array'],
            'reminders' => ['nullable', 'array'],
            'is_recurring' => ['boolean'],
            'recurrence_rule' => ['nullable', 'string'],
        ]);
        $task = $this->taskService->createWithReminders($data);
        event(new \App\Events\TaskCreated($task));
        return response()->json(['data' => $task], Response::HTTP_CREATED);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return response()->json(['data' => $task->load('project', 'assignee', 'subtasks', 'comments')]);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $data = $request->validate([
            'title' => ['sometimes','string','max:255'],
            'description' => ['nullable','string'],
            'status' => ['sometimes','in:todo,doing,done'],
            'priority' => ['sometimes','in:low,med,high'],
            'due_at' => ['nullable','date'],
            'start_at' => ['nullable','date'],
            'assignee_id' => ['nullable','exists:users,id'],
            'tags' => ['nullable','array'],
            'reminders' => ['nullable','array'],
            'is_recurring' => ['boolean'],
            'recurrence_rule' => ['nullable','string'],
        ]);
        $task = $this->taskService->updateWithReminders($task, $data);
        event(new TaskUpdated($task));
        return response()->json(['data' => $task]);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return response()->noContent();
    }

    public function move(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $data = $request->validate(['status' => ['required','in:todo,doing,done']]);
        $from = $task->status;
        $task->status = $data['status'];
        $task->save();
        event(new TaskMoved($task, $from, $task->status));
        return response()->json(['data' => $task]);
    }

    public function complete(Task $task)
    {
        $this->authorize('update', $task);
        $task->status = 'done';
        $task->completed_at = now();
        $task->save();
        event(new TaskCompleted($task));
        return response()->json(['data' => $task]);
    }
}