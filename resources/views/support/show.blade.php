@extends('layouts.app')

@section('title', $ticket->subject)

@section('content')
<section class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="support-breadcrumb">
            <a href="{{ route('support.index') }}">Мои обращения</a> / {{ $ticket->subject }}
        </div>

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="support-ticket-card">
                    <!-- Header -->
                    <div class="support-ticket-header">
                        <h1 class="support-ticket-title">{{ $ticket->subject }}</h1>
                        @if($ticket->status === 'open')
                            <span class="support-status-badge support-status-open">Открыто</span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="support-status-badge support-status-progress">В работе</span>
                        @elseif($ticket->status === 'resolved')
                            <span class="support-status-badge support-status-resolved">Решено</span>
                        @else
                            <span class="support-status-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">Закрыто</span>
                        @endif
                    </div>

                    <!-- User Meta -->
                    <div class="support-ticket-meta">
                        <img src="{{ $ticket->user->avatar_url }}" class="support-user-avatar" alt="{{ $ticket->user->name }}">
                        <div class="support-user-info">
                            <div class="support-user-name">{{ $ticket->user->display_name }}</div>
                            <div class="support-user-date">{{ $ticket->created_at->format('d.m.Y H:i') }}</div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="support-ticket-message">{{ $ticket->message }}</div>

                    <!-- Reply Form (for moderators) -->
                    @if(auth()->user()->isModerator())
                    <div class="support-reply-form">
                        <h5 class="support-reply-title">Ответить на обращение</h5>
                        <form method="POST" action="{{ route('support.reply', $ticket) }}">
                            @csrf
                            <textarea name="reply" class="form-control fh-form-control mb-3" rows="4" placeholder="Ваш ответ..." required></textarea>
                            <button type="submit" class="fh-btn fh-btn-primary">
                                <i class="bi bi-send me-2"></i>Отправить ответ
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="support-info-panel">
                    <div class="support-info-title">Информация</div>
                    
                    <div class="support-info-item">
                        <div class="support-info-label">Статус</div>
                        @if($ticket->status === 'open')
                            <span class="support-status-badge support-status-open">Открыто</span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="support-status-badge support-status-progress">В работе</span>
                        @elseif($ticket->status === 'resolved')
                            <span class="support-status-badge support-status-resolved">Решено</span>
                        @else
                            <span class="support-status-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">Закрыто</span>
                        @endif
                    </div>

                    <div class="support-info-item">
                        <div class="support-info-label">Приоритет</div>
                        @if($ticket->priority === 'high')
                            <span class="support-status-badge support-priority-high">Высокий</span>
                        @elseif($ticket->priority === 'medium')
                            <span class="support-status-badge support-priority-medium">Средний</span>
                        @else
                            <span class="support-status-badge support-priority-low">Низкий</span>
                        @endif
                    </div>

                    @if($ticket->assignedUser)
                    <div class="support-info-item">
                        <div class="support-info-label">Назначено</div>
                        <div class="support-info-value">{{ $ticket->assignedUser->display_name }}</div>
                    </div>
                    @endif

                    <div class="support-info-item">
                        <div class="support-info-label">Создано</div>
                        <div class="support-info-value">{{ $ticket->created_at->format('d.m.Y H:i') }}</div>
                    </div>

                    <div class="support-info-item">
                        <div class="support-info-label">Обновлено</div>
                        <div class="support-info-value">{{ $ticket->updated_at->format('d.m.Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection