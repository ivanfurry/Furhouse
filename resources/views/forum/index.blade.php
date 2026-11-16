@extends('layouts.app')

@section('title', 'Мини-форум')

@section('content')
<!-- Page Header -->
<section class="fh-page-header">
    <div class="container">
        <span class="fh-section-label">МИНИ-ФОРУМ</span>
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="fh-section-title">Разговоры внутри FurHouse</h1>
                <p class="fh-section-subtitle">Компактные доски, быстрые треды и дружелюбная модерация.</p>
            </div>
            @auth
                <a href="{{ route('forum.create') }}" class="fh-btn fh-btn-primary">
                    <i class="bi bi-plus me-2"></i>Создать тред
                </a>
            @else
                <a href="{{ route('login') }}" class="fh-btn fh-btn-primary">
                    <i class="bi bi-plus me-2"></i>Создать тред
                </a>
            @endauth
        </div>

        <!-- Stats bar -->
        <div class="d-flex justify-content-between align-items-center mt-4 p-3" style="background:rgba(124,92,156,0.08); border-radius:12px;">
            <div style="font-size:0.9rem; color:var(--fh-text-muted);">
                <i class="bi bi-clock me-2"></i>{{ $onlineCount }} участников онлайн · {{ $newThreadsToday }} новых тредов сегодня
            </div>
            <a href="{{ route('rules') }}" class="text-decoration-none" style="font-size:0.9rem; color:var(--fh-primary); font-weight:500;">Правила и безопасность</a>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="pb-5">
    <div class="container">
        <div class="row g-4">
            <!-- Boards Sidebar -->
            <div class="col-lg-3">
                <div class="fh-card p-3">
                    <h6 style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:var(--fh-text-muted); margin-bottom:12px;">Доски</h6>
                    
                    <a href="{{ route('forum.index') }}" class="fh-board-item {{ !request('board') ? 'active' : '' }}">
                        <span class="board-prefix">/fth/</span>
                        <span class="board-name">Общее</span>
                        <span class="board-count">128</span>
                    </a>
                    <a href="{{ route('forum.index', ['board' => 2]) }}" class="fh-board-item">
                        <span class="board-prefix">/art/</span>
                        <span class="board-name">Творчество</span>
                        <span class="board-count">84</span>
                    </a>
                    <a href="{{ route('forum.index', ['board' => 3]) }}" class="fh-board-item">
                        <span class="board-prefix">/craft/</span>
                        <span class="board-name">Крафт и костюмы</span>
                        <span class="board-count">56</span>
                    </a>
                    <a href="{{ route('forum.index', ['board' => 4]) }}" class="fh-board-item">
                        <span class="board-prefix">/meet/</span>
                        <span class="board-name">Встречи</span>
                        <span class="board-count">42</span>
                    </a>
                    <a href="{{ route('forum.index', ['board' => 5]) }}" class="fh-board-item">
                        <span class="board-prefix">/play/</span>
                        <span class="board-name">Игры</span>
                        <span class="board-count">37</span>
                    </a>
                    <a href="{{ route('forum.index', ['board' => 6]) }}" class="fh-board-item">
                        <span class="board-prefix">/start/</span>
                        <span class="board-name">Помощь новичкам</span>
                        <span class="board-count">21</span>
                    </a>

                    <hr class="my-3">
                    <a href="#" class="fh-board-item">
                        <i class="bi bi-bookmark me-2" style="color:var(--fh-primary);"></i>
                        <span class="board-name">Мои подписки</span>
                    </a>
                </div>
            </div>

            <!-- Threads -->
            <div class="col-lg-6">
                <!-- Search -->
                <div class="d-flex gap-2 mb-4">
                    <form method="GET" action="{{ route('forum.index') }}" class="fh-search flex-grow-1">
                        <i class="bi bi-search text-muted"></i>
                        <input type="text" name="search" placeholder="Поиск по тредам" value="{{ request('search') }}">
                        @if(request('board'))<input type="hidden" name="board" value="{{ request('board') }}">@endif
                    </form>
                    <button class="fh-btn fh-btn-icon fh-btn-ghost">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>

                <!-- Threads list -->
                @forelse($threads as $thread)
                <a href="{{ route('forum.show', $thread) }}" class="fh-thread-card">
                    <img src="{{ $thread->image ? asset('storage/' . $thread->image) : 'https://via.placeholder.com/240x180/e8e0f0/7c5c9c?text=' . urlencode($thread->board->name) }}" 
                         alt="{{ $thread->title }}">
                    <div class="flex-grow-1">
                        <div class="thread-meta mb-1">
                            <span style="color:var(--fh-primary); font-weight:700;">/{{ $thread->board->prefix }}/</span>
                            @if($thread->is_featured)
                                <span class="fh-badge fh-badge-primary ms-2" style="font-size:0.7rem;">В фокусе</span>
                            @endif
                            @if($thread->is_pinned)
                                <i class="bi bi-pin-fill text-warning ms-2"></i>
                            @endif
                        </div>
                        <h6 style="font-weight:600; margin-bottom:4px;">{{ $thread->title }}</h6>
                        <p class="text-muted mb-2" style="font-size:0.85rem; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                            {{ Str::limit($thread->body, 120) }}
                        </p>
                        <div class="thread-meta">
                            {{ $thread->user->name }} · {{ $thread->created_at->diffForHumans() }}
                        </div>
                        <div class="thread-stats">
                            <span><i class="bi bi-chat me-1"></i>{{ $thread->replies_count }}</span>
                            <span><i class="bi bi-eye me-1"></i>{{ $thread->views_count }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-chat-square-text" style="font-size:3rem; color:var(--fh-border);"></i>
                    <p class="text-muted mt-3">Треды не найдены</p>
                </div>
                @endforelse

                @if($threads->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $threads->links() }}
                </div>
                @endif

                <button class="fh-btn fh-btn-ghost w-100 mt-3">Показать ещё</button>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-3">
                <!-- Create thread CTA -->
                <div class="fh-info-panel mb-4">
                    <div style="width:44px; height:44px; border-radius:12px; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                        <i class="bi bi-chat-square-dots" style="font-size:1.3rem;"></i>
                    </div>
                    <h5>Начните разговор</h5>
                    <p>Выберите доску, добавьте короткий заголовок и превью.</p>
                    @auth
                        <a href="{{ route('forum.create') }}" class="fh-btn fh-btn-outline w-100 text-center" style="background:white; color:var(--fh-primary-dark); border:none;">
                            Новый тред
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="fh-btn fh-btn-outline w-100 text-center" style="background:white; color:var(--fh-primary-dark); border:none;">
                            Войти
                        </a>
                    @endauth
                </div>

                <!-- Rules -->
                <div class="fh-info-panel-light mb-4">
                    <h6>Здесь спокойно</h6>
                    <div class="fh-rule-item">
                        <i class="bi bi-heart-fill"></i>
                        <span>Уважайте собеседников</span>
                    </div>
                    <div class="fh-rule-item">
                        <i class="bi bi-shield-fill-check"></i>
                        <span>Без контента 18+</span>
                    </div>
                    <div class="fh-rule-item">
                        <i class="bi bi-flag-fill"></i>
                        <span>Сообщайте о нарушениях</span>
                    </div>
                </div>

                <!-- Contact -->
                <div class="fh-info-panel-light">
                    <h6>На связи</h6>
                    <p style="font-size:0.9rem; margin-bottom:12px;">3 модератора онлайн</p>
                    <a href="#" class="fh-btn fh-btn-ghost w-100 text-center">
                        <i class="bi bi-chat-dots me-2"></i>Написать
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection