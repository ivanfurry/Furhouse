@extends('layouts.app')

@section('title', $event->title)

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:0.9rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color:var(--fh-primary);">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}" class="text-decoration-none" style="color:var(--fh-primary);">Встречи</a></li>
                <li class="breadcrumb-item active">{{ $event->title }}</li>
            </ol>
        </nav>

        <div class="event-show-layout">
            <!-- Main Content -->
            <div>
                <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=900&h=500&fit=crop' }}" 
                     alt="{{ $event->title }}" 
                     class="event-main-image">
                
                <div class="event-badges">
                    <span class="fh-badge fh-badge-primary">{{ $event->type_label }}</span>
                    @if($event->is_verified)
                        <span class="fh-badge fh-badge-success"><i class="bi bi-shield-check me-1"></i>Проверено</span>
                    @endif
                </div>

                <h1 class="event-title">{{ $event->title }}</h1>

                <div class="event-meta">
                    <div class="event-meta-item">
                        <i class="bi bi-calendar"></i>
                        <span>{{ $event->starts_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="event-meta-item">
                        <i class="bi bi-geo-alt"></i>
                        <span>{{ $event->city }}{{ $event->location ? ', ' . $event->location : '' }}</span>
                    </div>
                    <div class="event-meta-item">
                        <i class="bi bi-people"></i>
                        <span>{{ $event->participants_count }} / {{ $event->max_guests ?? '∞' }} гостей</span>
                    </div>
                    <div class="event-meta-item">
                        <i class="bi bi-tag"></i>
                        <span>{{ $event->is_free ? 'Бесплатно' : number_format($event->price, 0, '.', ' ') . ' ₽' }}</span>
                    </div>
                </div>

                <div class="event-description">
                    {!! nl2br(e($event->description)) !!}
                </div>

                @if($event->participants->count() > 0)
                <div class="event-participants">
                    <h3 class="event-participants-title">Участники ({{ $event->participants_count }})</h3>
                    <div class="event-participants-avatars">
                        @foreach($event->participants->take(12) as $participant)
                            <img src="{{ $participant->user->avatar_url }}" 
                                 class="event-participant-avatar" 
                                 title="{{ $participant->user->name }}">
                        @endforeach
                        @if($event->participants->count() > 12)
                            <div class="event-participants-more">
                                +{{ $event->participants->count() - 12 }}
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div>
                <div class="event-sidebar-card">
                    <div class="event-organizer">
                        <img src="{{ $event->user->avatar_url }}" class="event-organizer-avatar" alt="">
                        <div>
                            <div class="event-organizer-label">Организатор</div>
                            <div class="event-organizer-name">{{ $event->user->display_name }}</div>
                            @if($event->user->is_verified)
                                <span class="fh-badge fh-badge-success" style="font-size:0.7rem; margin-top:4px;">
                                    <i class="bi bi-shield-check me-1"></i>Проверен
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="event-info-box">
                        <div class="event-info-row">
                            <span class="event-info-label">Дата</span>
                            <span class="event-info-value">{{ $event->starts_at->format('d M') }}</span>
                        </div>
                        <div class="event-info-row">
                            <span class="event-info-label">Время</span>
                            <span class="event-info-value">{{ $event->starts_at->format('H:i') }}</span>
                        </div>
                        <div class="event-info-row">
                            <span class="event-info-label">Место</span>
                            <span class="event-info-value">{{ $event->city }}</span>
                        </div>
                    </div>

                    @auth
                        @if($isParticipant)
                            <form method="POST" action="{{ route('events.cancel', $event) }}">
                                @csrf
                                <button type="submit" class="event-action-btn event-action-btn--ghost">
                                    <i class="bi bi-x-circle"></i>Отменить участие
                                </button>
                            </form>
                            <div class="event-participation-status">
                                <i class="bi bi-check-circle me-1"></i>Вы участвуете
                            </div>
                        @else
                            <form method="POST" action="{{ route('events.participate', $event) }}">
                                @csrf
                                <button type="submit" class="event-action-btn event-action-btn--primary" 
                                        {{ $event->max_guests && $event->participants_count >= $event->max_guests ? 'disabled' : '' }}>
                                    <i class="bi bi-check-circle"></i>Записаться
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="event-action-btn event-action-btn--primary text-center">
                            Войти для записи
                        </a>
                    @endauth

                    <div class="event-rules">
                        <div class="event-rules-title">Правила встречи</div>
                        <ul class="event-rules-list">
                            <li>Уважайте всех участников</li>
                            <li>Следуйте кодексу сообщества</li>
                            <li>Приходите вовремя</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection