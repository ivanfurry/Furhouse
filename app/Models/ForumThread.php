<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ForumThread extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'board_id',
        'user_id',
        'title',
        'slug',
        'body',
        'image',
        'is_pinned',
        'is_locked',
        'is_featured',
        'status',
        'views_count',
        'replies_count',
        'last_activity_at',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'is_featured' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($thread) {
            if (empty($thread->slug)) {
                $thread->slug = Str::slug($thread->title) . '-' . Str::random(6);
            }
        });
    }

    public function board(): BelongsTo
    {
        return $this->belongsTo(ForumBoard::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(ForumPost::class);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}