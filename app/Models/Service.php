<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'category',
        'price_from',
        'deadline',
        'rating',
        'reviews_count',
        'is_active',
        'accepts_orders',
        'orders_count',
        'status',
    ];

    protected $casts = [
        'price_from' => 'decimal:2',
        'rating' => 'decimal:1',
        'is_active' => 'boolean',
        'accepts_orders' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title) . '-' . Str::random(6);
            }
        });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('status', 'approved'); // ← добавить проверку статуса
    }

    public function scopeAcceptingOrders($query)
    {
        return $query->where('accepts_orders', true);
    }

    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('user', function ($uq) use ($search) {
                  $uq->where('name', 'like', "%{$search}%")
                     ->orWhere('nickname', 'like', "%{$search}%");
              });
        });
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'art' => 'Арт',
            'costume' => 'Костюм',
            '3d' => '3D',
            'music' => 'Музыка',
            'design' => 'Дизайн',
            'craft' => 'Крафт',
            default => 'Другое',
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'от ' . number_format($this->price_from, 0, '.', ' ') . ' ₽';
    }
}