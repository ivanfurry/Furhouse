<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'priority',
        'is_pinned',
        'expires_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'Срочно',
            'important' => 'Важно',
            default => 'Обычное',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'var(--fh-danger)',
            'important' => 'var(--fh-warning)',
            default => 'var(--fh-primary)',
        };
    }
}