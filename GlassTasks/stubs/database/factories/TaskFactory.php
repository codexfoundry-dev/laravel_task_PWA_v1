<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $status = Arr::random(['todo', 'doing', 'done']);
        $due = $this->faker->boolean(70) ? Carbon::now()->addDays(rand(-5, 10)) : null;
        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->boolean(60) ? $this->faker->paragraph() : null,
            'status' => $status,
            'priority' => Arr::random(['low','med','high']),
            'due_at' => $due,
            'start_at' => $due ? Carbon::parse($due)->subHours(rand(1,48)) : null,
            'estimated_minutes' => rand(15, 240),
            'actual_minutes' => $status === 'done' ? rand(15, 240) : 0,
            'assignee_id' => User::factory(),
            'tags' => $this->faker->randomElements(['home','work','errand','urgent','idea'], rand(0,3)),
            'reminders' => ['1d','1h','10m'],
            'is_recurring' => $this->faker->boolean(10),
            'recurrence_rule' => 'FREQ=WEEKLY;INTERVAL=1',
            'completed_at' => $status === 'done' ? Carbon::now()->subDays(rand(0,5)) : null,
        ];
    }
}