<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function view(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id || $project->members()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }
}