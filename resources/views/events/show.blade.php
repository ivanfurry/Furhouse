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

        <div class="row g-5">
            <div class="col-lg-8">
                <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=900&h=500&fit=crop' }}" 
                     alt="{{ $event->title }}" 
                     style="width:100%; border-radius:var(--fh-radius); margin-bottom:24px;">

                <span class="fh-badge fh-badge-primary mb-3">{{ $event->type_label }}</span>
                @if($event->is_verified)
                    <span class="fh-badge fh-badge-success mb-3"><i class="bi bi-shield-check me-1"></i>Проверено</span>
                @endif

                <h1 style="font-size:2.2rem; font-weight:400; margin-bottom:16px;">{{ $event->title }}</h1>

                <div class="d-flex flex-wrap gap-4 mb-4" style="font-size:0.95rem; color:var(--fh-text-muted);">
                    <span><i class="bi bi-calendar me-2"></i>{{ $event->starts_at->format('d M Y, H:i') }}</span>
                    <span><i class="bi bi-geo-alt me-2"></i>{{ $event->city }}{{ $event->location ? ', ' . $event->location : '' }}</span>
                    <span><i class="bi bi-people me-2"></i>{{ $event->participants_count }} / {{ $event->max_guests ?? '∞' }} гостей</span>
                    <span><i class="bi bi-tag me-2"></i>{{ $event->is_free ? 'Бесплатно' : number_format($event->price, 0, '.', ' ') . ' ₽' }}</span>
                </div>

                <div style="line-height:1.8; font-size:1rem; color:var(--fh-text);">
                    {!! nl2br(e($event->description)) !!}
                </div>

                @if($event->participants->count() > 0)
                <div class="mt-5">
                    <h3 style="font-size:1.3rem; font-weight:600; margin-bottom:16px;">Участники ({{ $event->participants_count }})</h3>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($event->participants->take(12) as $participant)
                            <img src="{{ $participant->user->avatar_url }}" class="fh-avatar" title="{{ $participant->user->name }}" style="border:2px solid white; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                        @endforeach
                        @if($event->participants->count() > 12)
                            <div class="fh-avatar d-flex align-items-center justify-content-center" style="background:var(--fh-primary); color:white; font-weight:600; font-size:0.85rem;">
                                +{{ $event->participants->count() - 12 }}
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="fh-card p-4 sticky-top" style="top:90px;">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ $event->user->avatar_url }}" class="fh-avatar fh-avatar-lg" alt="">
                        <div>
                            <div style="font-size:0.75rem; color:var(--fh-text-muted); text-transform:uppercase; letter-spacing:0.5px;">Организатор</div>
                            <div style="font-weight:600;">{{ $event->user->display_name }}</div>
                            @if($event->user->is_verified)
                                <span class="fh-badge fh-badge-success" style="font-size:0.7rem; margin-top:4px;"><i class="bi bi-shield-check me-1"></i>Проверен</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4" style="padding:16px; background:var(--fh-bg); border-radius:12px;">
                        <div class="d-flex justify-content-between mb-2">
                            <span style="font-size:0.85rem; color:var(--fh-text-muted);">Дата</span>
                            <span style="font-weight:600;">{{ $event->starts_at->format('d M') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span style="font-size:0.85rem; color:var(--fh-text-muted);">Время</span>
                            <span style="font-weight:600;">{{ $event->starts_at->format('H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="font-size:0.85rem; color:var(--fh-text-muted);">Место</span>
                            <span style="font-weight:600; text-align:right;">{{ $event->city }}</span>
                        </div>
                    </div>

                    @auth
                        @if($isParticipant)
                            <form method="POST" action="{{ route('events.cancel', $event) }}">
                                @csrf
                                <button type="submit" class="fh-btn fh-btn-ghost w-100 py-3">
                                    <i class="bi bi-x-circle me-2"></i>Отменить участие
                                </button>
                            </form>
                            <div class="text-center mt-3">
                                <span class="fh-badge fh-badge-success w-100"><i class="bi bi-check-circle me-1"></i>Вы участвуете</span>
                            </div>
                        @else
                            <form method="POST" action="{{ route('events.participate', $event) }}">
                                @csrf
                                <button type="submit" class="fh-btn fh-btn-primary w-100 py-3" {{ $event->max_guests && $event->participants_count >= $event->max_guests ? 'disabled' : '' }}>
                                    <i class="bi bi-check-circle me-2"></i>Записаться
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="fh-btn fh-btn-primary w-100 py-3 text-center">
                            Войти для записи
                        </a>
                    @endauth

                    <div class="mt-4 pt-4" style="border-top:1px solid var(--fh-border);">
                        <h6 style="font-size:0.85rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:var(--fh-text-muted); margin-bottom:12px;">Правила встречи</h6>
                        <ul style="font-size:0.9rem; color:var(--fh-text-muted); padding-left:20px; margin:0;">
                            <li class="mb-2">Уважайте всех участников</li>
                            <li class="mb-2">Следуйте кодексу сообщества</li>
                            <li>Приходите вовремя</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection