@extends('layouts.app')

@section('title', $ticket->subject)

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:0.9rem;">
                <li class="breadcrumb-item">
                    <a href="{{ auth()->user()->isModerator() ? route('staff.support.index') : route('support.chat.index') }}" 
                       class="text-decoration-none" style="color:var(--fh-primary);">
                        Обращения
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ $ticket->subject }}</li>
            </ol>
        </nav>

        <div class="support-chat-layout">
            <!-- Chat Area -->
            <div class="support-chat-card">
                <!-- Header -->
                <div class="support-chat-header">
                    <div>
                        <h2 class="support-chat-title">{{ $ticket->subject }}</h2>
                        <div class="support-chat-meta">
                            Создано {{ $ticket->created_at->format('d.m.Y H:i') }}
                            @if($ticket->assignedUser)
                                · Назначено: {{ $ticket->assignedUser->name }}
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($ticket->status === 'open')
                            <span class="fh-badge fh-badge-warning">Открыто</span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="fh-badge fh-badge-primary">В работе</span>
                        @elseif($ticket->status === 'resolved')
                            <span class="fh-badge fh-badge-success">Решено</span>
                        @endif
                    </div>
                </div>

                <!-- Messages -->
                <div class="support-chat-messages">
                    @forelse($ticket->messages as $message)
                    <div class="support-message {{ $message->user_id === auth()->id() ? 'support-message--own' : 'support-message--other' }}">
                        <img src="{{ $message->user->avatar_url }}" class="support-message-avatar" alt="">
                        <div class="support-message-content">
                            <div class="support-message-header">
                                <span class="support-message-author">{{ $message->user->name }}</span>
                                @if($message->is_staff)
                                    <span class="support-message-staff-badge">Персонал</span>
                                @endif
                                <span class="support-message-time">{{ $message->created_at->format('H:i') }}</span>
                            </div>
                            <div class="support-message-bubble">
                                {{ $message->message }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="support-chat-empty">
                        <i class="bi bi-chat-dots"></i>
                        <p>Начните диалог</p>
                    </div>
                    @endforelse
                </div>

                <!-- Resolved Banner -->
                @if($ticket->status === 'resolved' && !auth()->user()->isModerator())
                <div class="support-chat-resolved-banner">
                    <span class="fh-badge fh-badge-success">Обращение решено</span>
                    <p>Если у вас остались вопросы, создайте новое обращение</p>
                </div>
                @endif

                <!-- Reply Form -->
                @if($ticket->status !== 'resolved' || auth()->user()->isModerator())
                <div class="support-chat-reply">
                    <form method="POST" action="{{ route('support.chat.message', $ticket) }}" class="support-chat-reply-form">
                        @csrf
                        <textarea name="message" 
                                  class="support-chat-reply-textarea" 
                                  placeholder="Напишите сообщение..." 
                                  required
                                  rows="2"></textarea>
                        <button type="submit" class="support-chat-reply-btn" title="Отправить">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="support-chat-sidebar">
                <div class="support-chat-sidebar-title">Информация</div>

                <div class="support-chat-info-item">
                    <div class="support-chat-info-label">Статус</div>
                    @if($ticket->status === 'open')
                        <span class="fh-badge fh-badge-warning">Открыто</span>
                    @elseif($ticket->status === 'in_progress')
                        <span class="fh-badge fh-badge-primary">В работе</span>
                    @elseif($ticket->status === 'resolved')
                        <span class="fh-badge fh-badge-success">Решено</span>
                    @endif
                </div>

                <div class="support-chat-info-item">
                    <div class="support-chat-info-label">Приоритет</div>
                    @if($ticket->priority === 'high')
                        <span class="fh-badge" style="background:rgba(196,92,92,0.12); color:var(--fh-danger);">Высокий</span>
                    @elseif($ticket->priority === 'medium')
                        <span class="fh-badge fh-badge-warning">Средний</span>
                    @else
                        <span class="fh-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">Низкий</span>
                    @endif
                </div>

                <div class="support-chat-info-item">
                    <div class="support-chat-info-label">Автор</div>
                    <div class="support-chat-info-value">{{ $ticket->user->display_name }}</div>
                </div>

                @if($ticket->assignedUser)
                <div class="support-chat-info-item">
                    <div class="support-chat-info-label">Назначено</div>
                    <div class="support-chat-info-value">{{ $ticket->assignedUser->display_name }}</div>
                </div>
                @endif

                <div class="support-chat-info-item">
                    <div class="support-chat-info-label">Создано</div>
                    <div class="support-chat-info-value">{{ $ticket->created_at->format('d.m.Y H:i') }}</div>
                </div>

                <div class="support-chat-info-item">
                    <div class="support-chat-info-label">Обновлено</div>
                    <div class="support-chat-info-value">{{ $ticket->updated_at->format('d.m.Y H:i') }}</div>
                </div>

                <!-- Actions for staff -->
                @if(auth()->user()->isModerator())
                <div class="support-chat-actions">
                    @if(!$ticket->assigned_to)
                    <form method="POST" action="{{ route('staff.support.assign', $ticket) }}">
                        @csrf
                        <button type="submit" class="fh-btn fh-btn-outline w-100">
                            <i class="bi bi-person-check me-2"></i>Назначить себе
                        </button>
                    </form>
                    @endif

                    @if($ticket->status !== 'resolved')
                    <form method="POST" action="{{ route('staff.support.close', $ticket) }}">
                        @csrf
                        <button type="submit" class="fh-btn fh-btn-primary w-100" onclick="return confirm('Закрыть обращение?')">
                            <i class="bi bi-check-circle me-2"></i>Закрыть обращение
                        </button>
                    </form>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection