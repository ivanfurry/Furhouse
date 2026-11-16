@extends('layouts.app')

@section('title', $user->display_name)

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:0.9rem;">
                <li class="breadcrumb-item"><a href="{{ route('team') }}" class="text-decoration-none" style="color:var(--fh-primary);">Команда</a></li>
                <li class="breadcrumb-item active">{{ $user->display_name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="fh-card p-4 text-center">
                    <img src="{{ $user->avatar_url }}" class="fh-avatar fh-avatar-xl mx-auto mb-3" alt="{{ $user->name }}">
                    <h3 style="font-size:1.4rem; font-weight:600; margin-bottom:4px;">{{ $user->display_name }}</h3>
                    @if($user->nickname)
                        <p class="text-muted mb-3">{{ '@' . $user->nickname }}</p>
                    @endif
                    <div class="fh-badge fh-badge-primary mb-3">
                        {{ $user->role === 'admin' ? 'Администратор' : 'Модератор' }}
                    </div>
                    <p class="text-muted" style="font-size:0.9rem;">
                        В команде с {{ $user->created_at->format('M Y') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="fh-card p-4">
                    <h4 style="font-weight:600; margin-bottom:16px;">О пользователе</h4>
                    <p style="line-height:1.7; color:var(--fh-text-muted);">
                        {{ $user->bio ?? 'Участник команды FurHouse. Занимается модерацией и поддержкой сообщества.' }}
                    </p>
                </div>

                @auth
                <div class="fh-card p-4 mt-4">
                    <h4 style="font-weight:600; margin-bottom:16px;">Связаться</h4>
                    <a href="{{ route('support.create', ['to' => $user->id]) }}" class="fh-btn fh-btn-primary">
                        <i class="bi bi-chat-dots me-2"></i>Написать сообщение
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection