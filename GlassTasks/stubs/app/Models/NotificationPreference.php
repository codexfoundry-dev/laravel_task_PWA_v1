<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'email', 'push', 'in_app', 'daily_digest_hour'
    ];

    protected $casts = [
        'email' => 'boolean',
        'push' => 'boolean',
        'in_app' => 'boolean',
        'daily_digest_hour' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}