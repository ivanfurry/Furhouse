<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumPost extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'thread_id',
        'user_id',
        'body',
        'is_solution',
    ];

    protected $casts = [
        'is_solution' => 'boolean',
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(ForumThread::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}