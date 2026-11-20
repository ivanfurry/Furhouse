<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        'priority',
        'assigned_to',
        'service_id',
        'type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    /**
     * Проверка доступа к обращению
     */
    public function canUserView(User $user): bool
    {
        // Автор обращения всегда видит свой тикет
        if ($this->user_id === $user->id) {
            return true;
        }

        // Модераторы и админы видят все
        if ($user->isModerator()) {
            return true;
        }

        // Мастер видит обращения, назначенные на него
        if ($this->assigned_to === $user->id) {
            return true;
        }

        // Мастер видит обращения по своим услугам
        if ($this->service && $this->service->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function isAssignedTo(User $user): bool
    {
        return $this->assigned_to === $user->id;
    }

    public function isServiceDiscussion(): bool
    {
        return $this->type === 'service_discussion';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open' => 'Открыто',
            'in_progress' => 'В работе',
            'resolved' => 'Решено',
            'closed' => 'Закрыто',
            default => 'Неизвестно',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'open' => '#d4a853',
            'in_progress' => '#7c5c9c',
            'resolved' => '#6b9e7a',
            'closed' => '#6b6b6b',
            default => '#6b6b6b',
        };
    } 
}