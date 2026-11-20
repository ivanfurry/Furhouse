@extends('layouts.app')

@section('title', $ticket->subject)

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:0.9rem;">
                <li class="breadcrumb-item">
                    <a href="{{ route('service-discussion.master-index') }}" 
                       class="text-decoration-none" style="color:var(--fh-primary);">
                        Обсуждения
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
                            @if($ticket->service)
                                Услуга: {{ $ticket->service->title }}
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($ticket->status === 'open')
                            <span class="fh-badge fh-badge-warning">Открыто</span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="fh-badge fh-badge-primary">В обсуждении</span>
                        @elseif($ticket->status === 'resolved')
                            <span class="fh-badge fh-badge-success">Завершено</span>
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
                                    <span class="support-message-staff-badge">Мастер</span>
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
                        <p>Начните обсуждение</p>
                    </div>
                    @endforelse
                </div>

                <!-- Resolved Banner -->
                @if($ticket->status === 'resolved')
                <div class="support-chat-resolved-banner">
                    <span class="fh-badge fh-badge-success">Обсуждение завершено</span>
                    <p>Если у вас остались вопросы, создайте новое обсуждение</p>
                </div>
                @endif

                <!-- Reply Form -->
                @if($ticket->status !== 'resolved')
                <div class="support-chat-reply">
                    <form method="POST" action="{{ route('service-discussion.message', $ticket) }}" class="support-chat-reply-form">
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
                        <span class="fh-badge fh-badge-primary">В обсуждении</span>
                    @elseif($ticket->status === 'resolved')
                        <span class="fh-badge fh-badge-success">Завершено</span>
                    @endif
                </div>

                @if($ticket->service)
                <div class="support-chat-info-item">
                    <div class="support-chat-info-label">Услуга</div>
                    <div class="support-chat-info-value">{{ $ticket->service->title }}</div>
                    <div style="font-size:0.9rem; color:var(--fh-primary-dark); font-weight:700;">
                        от {{ number_format($ticket->service->price, 0, '.', ' ') }} ₽
                    </div>
                </div>
                @endif

                <div class="support-chat-info-item">
                    <div class="support-chat-info-label">
                        {{ $ticket->service && $ticket->service->user_id === auth()->id() ? 'Клиент' : 'Мастер' }}
                    </div>
                    <div class="support-chat-info-value">
                        {{ $ticket->service && $ticket->service->user_id === auth()->id() ? $ticket->user->display_name : $ticket->assignedUser->display_name }}
                    </div>
                </div>

                <div class="support-chat-info-item">
                    <div class="support-chat-info-label">Создано</div>
                    <div class="support-chat-info-value">{{ $ticket->created_at->format('d.m.Y H:i') }}</div>
                </div>

                <div class="support-chat-info-item">
                    <div class="support-chat-info-label">Обновлено</div>
                    <div class="support-chat-info-value">{{ $ticket->updated_at->format('d.m.Y H:i') }}</div>
                </div>

                <!-- Actions for master -->
                @if($ticket->service && $ticket->service->user_id === auth()->id() && $ticket->status !== 'resolved')
                <div class="support-chat-actions">
                    <form method="POST" action="{{ route('service-discussion.close', $ticket) }}">
                        @csrf
                        <button type="submit" class="fh-btn fh-btn-primary w-100" onclick="return confirm('Завершить обсуждение?')">
                            <i class="bi bi-check-circle me-2"></i>Завершить обсуждение
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection