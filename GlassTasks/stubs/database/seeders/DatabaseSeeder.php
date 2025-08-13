<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\NotificationPreference;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory()->count(3)->create();
        $owner = $users->first();

        $projects = Project::factory()->count(3)->create([
            'owner_id' => $owner->id,
        ]);

        foreach ($projects as $project) {
            $project->members()->sync($users->pluck('id'));
            Task::factory()->count(7)->create([
                'project_id' => $project->id,
                'assignee_id' => $users->random()->id,
            ]);
        }

        foreach ($users as $user) {
            NotificationPreference::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'email' => true,
                'push' => true,
                'in_app' => true,
                'daily_digest_hour' => 8,
            ]);
        }
    }
}