<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Project;

Broadcast::channel('projects.{projectId}', function ($user, int $projectId) {
    $project = Project::find($projectId);
    if (!$project) return false;
    return $project->owner_id === $user->id || $project->members()->where('user_id', $user->id)->exists();
});