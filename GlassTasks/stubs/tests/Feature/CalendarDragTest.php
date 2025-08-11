<?php

use App\Models\Task;
use App\Models\User;

it('updates due_at on drag', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $task = Task::factory()->create(['due_at' => now()->addDay()]);

    $new = now()->addDays(2);
    $this->putJson("/api/v1/tasks/{$task->id}", ['due_at' => $new])->assertOk();
    $task->refresh();
    expect($task->due_at->toDateTimeString())->toBe($new->toDateTimeString());
});