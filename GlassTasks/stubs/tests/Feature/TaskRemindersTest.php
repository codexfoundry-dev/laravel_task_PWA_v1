<?php

use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;

it('creates task with reminders', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $service = app(TaskService::class);
    $task = $service->createWithReminders([
        'project_id' => 1,
        'title' => 'Test',
        'status' => 'todo',
        'priority' => 'med',
        'due_at' => now()->addHour(),
        'reminders' => ['10m'],
    ]);

    expect($task->id)->not()->toBeNull();
});