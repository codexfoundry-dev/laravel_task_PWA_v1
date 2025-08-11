<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'eventable_id', 'eventable_type', 'title', 'start', 'end', 'all_day', 'meta'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'all_day' => 'boolean',
        'meta' => 'array',
    ];

    public function eventable(): MorphTo
    {
        return $this->morphTo();
    }
}