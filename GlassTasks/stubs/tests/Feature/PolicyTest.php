<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Task;

it('enforces project policy', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->create(['owner_id' => $owner->id]);
    $member = User::factory()->create();
    $project->members()->attach($member->id);

    $this->actingAs($member);
    $this->getJson("/api/v1/projects/{$project->id}")->assertOk();
});