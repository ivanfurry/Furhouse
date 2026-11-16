<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'avatar',
        'role',
        'bio',
        'city',
        'is_verified',
        'is_active',
        'can_post_products',
        'can_post_services',
        'can_create_events',
        'can_post_forum',
        'can_comment',
        'restriction_reason',
        'restriction_until',
        'restricted_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Roles
    const ROLE_USER = 'user';
    const ROLE_MASTER = 'master';
    const ROLE_ORGANIZER = 'organizer';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_ADMIN = 'admin';

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isModerator(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MODERATOR]);
    }

    public function isMaster(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MASTER]);
    }

    public function isOrganizer(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_ORGANIZER]);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function forumThreads(): HasMany
    {
        return $this->hasMany(ForumThread::class);
    }

    public function forumPosts(): HasMany
    {
        return $this->hasMany(ForumPost::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->nickname ? "{$this->name} «{$this->nickname}»" : $this->name;
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default-avatar.svg');
    }

    public function hasRestrictions(): bool
    {
        // Проверяем, есть ли хоть одно ограничение
        return !$this->can_post_products 
            || !$this->can_post_services 
            || !$this->can_create_events 
            || !$this->can_post_forum 
            || !$this->can_comment;
    }

    public function getRestrictionsList(): array
    {
        $restrictions = [];
        
        if (!$this->can_post_products) {
            $restrictions[] = 'Размещение товаров';
        }
        if (!$this->can_post_services) {
            $restrictions[] = 'Размещение услуг';
        }
        if (!$this->can_create_events) {
            $restrictions[] = 'Создание встреч';
        }
        if (!$this->can_post_forum) {
            $restrictions[] = 'Публикация в форуме';
        }
        if (!$this->can_comment) {
            $restrictions[] = 'Комментирование';
        }
        
        return $restrictions;
    }

    public function isRestricted(): bool
    {
        return $this->hasRestrictions();
    }

    public function getRestrictionEndDate(): ?string
    {
        if ($this->restriction_until) {
            return \Carbon\Carbon::parse($this->restriction_until)->format('d.m.Y H:i');
        }
        return 'Бессрочно';
    }
}