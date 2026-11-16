<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumBoard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'prefix',
        'description',
        'sort_order',
        'threads_count',
        'posts_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function threads(): HasMany
    {
        return $this->hasMany(ForumThread::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}