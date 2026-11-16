<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'short_description',
        'image',
        'type',
        'city',
        'location',
        'starts_at',
        'ends_at',
        'max_guests',
        'price',
        'is_free',
        'is_verified',
        'is_featured',
        'status',
        'participants_count',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'price' => 'decimal:2',
        'is_free' => 'boolean',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title) . '-' . Str::random(6);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>=', now())
                     ->where('status', 'approved')
                     ->orderBy('starts_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'walk' => 'Прогулка',
            'picnic' => 'Пикник',
            'creative' => 'Творчество',
            'games' => 'Игры',
            'festival' => 'Фестиваль',
            default => 'Другое',
        };
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->starts_at->format('d M, H:i');
    }
}