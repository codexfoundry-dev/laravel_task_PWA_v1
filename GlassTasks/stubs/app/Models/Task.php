<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'title', 'description', 'status', 'priority', 'due_at', 'start_at',
        'estimated_minutes', 'actual_minutes', 'assignee_id', 'tags', 'reminders',
        'is_recurring', 'recurrence_rule', 'completed_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'start_at' => 'datetime',
        'completed_at' => 'datetime',
        'tags' => 'array',
        'reminders' => 'array',
        'is_recurring' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    protected function isOverdue(): Attribute
    {
        return Attribute::get(fn () => $this->due_at && $this->due_at->isPast() && !$this->completed_at);
    }
}