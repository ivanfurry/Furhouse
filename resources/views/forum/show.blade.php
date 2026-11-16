@extends('layouts.app')

@section('title', $thread->title)

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:0.9rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color:var(--fh-primary);">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('forum.index') }}" class="text-decoration-none" style="color:var(--fh-primary);">Форум</a></li>
                <li class="breadcrumb-item active">{{ $thread->title }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-9">
                <!-- Thread -->
                <div class="fh-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span style="color:var(--fh-primary); font-weight:700; font-size:0.9rem;">/{{ $thread->board->prefix }}/</span>
                            @if($thread->is_featured)
                                <span class="fh-badge fh-badge-primary ms-2">В фокусе</span>
                            @endif
                            @if($thread->is_pinned)
                                <span class="fh-badge fh-badge-warning ms-2"><i class="bi bi-pin-fill me-1"></i>Закреплено</span>
                            @endif
                            @if($thread->is_locked)
                                <span class="fh-badge" style="background:rgba(196,92,92,0.12); color:var(--fh-danger);"><i class="bi bi-lock-fill me-1"></i>Заблокировано</span>
                            @endif
                        </div>
                        @auth
                            @if(auth()->user()->isModerator())
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('forum.pin', $thread) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="fh-btn fh-btn-ghost" style="padding:6px 12px; font-size:0.85rem;">
                                            <i class="bi bi-pin me-1"></i>{{ $thread->is_pinned ? 'Открепить' : 'Закрепить' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('forum.lock', $thread) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="fh-btn fh-btn-ghost" style="padding:6px 12px; font-size:0.85rem;">
                                            <i class="bi bi-lock me-1"></i>{{ $thread->is_locked ? 'Разблокировать' : 'Заблокировать' }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <h1 style="font-size:1.6rem; font-weight:500; margin-bottom:16px;">{{ $thread->title }}</h1>

                    <div class="d-flex align-items-center gap-3 mb-4 pb-4" style="border-bottom:1px solid var(--fh-border);">
                        <img src="{{ $thread->user->avatar_url }}" class="fh-avatar fh-avatar-lg" alt="">
                        <div>
                            <div style="font-weight:600;">{{ $thread->user->display_name }}</div>
                            <div style="font-size:0.85rem; color:var(--fh-text-muted);">
                                {{ $thread->created_at->format('d M Y, H:i') }} · 
                                <i class="bi bi-eye me-1"></i>{{ $thread->views_count }} просмотров
                            </div>
                        </div>
                    </div>

                    @if($thread->image)
                        <img src="{{ asset('storage/' . $thread->image) }}" class="w-100 mb-4" style="border-radius:12px; max-height:400px; object-fit:cover;">
                    @endif

                    <div style="line-height:1.8; font-size:1rem;">
                        {!! nl2br(e($thread->body)) !!}
                    </div>
                </div>

                <!-- Posts -->
                @foreach($posts as $post)
                <div class="fh-card p-4 mb-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $post->user->avatar_url }}" class="fh-avatar" alt="">
                        <div>
                            <div style="font-weight:600; font-size:0.95rem;">{{ $post->user->display_name }}</div>
                            <div style="font-size:0.8rem; color:var(--fh-text-muted);">{{ $post->created_at->diffForHumans() }}</div>
                        </div>
                        @if($post->is_solution)
                            <span class="fh-badge fh-badge-success ms-auto"><i class="bi bi-check-circle me-1"></i>Решение</span>
                        @endif
                    </div>
                    <div style="line-height:1.7;">
                        {!! nl2br(e($post->body)) !!}
                    </div>
                </div>
                @endforeach

                @if($posts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->links() }}
                </div>
                @endif

                <!-- Reply form -->
                @auth
                    @if(!$thread->is_locked)
                    <div class="fh-card p-4 mt-4">
                        <h5 style="font-weight:600; margin-bottom:16px;">Ответить</h5>
                        <form method="POST" action="{{ route('forum.post', $thread) }}">
                            @csrf
                            <textarea name="body" class="form-control fh-form-control mb-3" rows="4" placeholder="Ваш ответ..." required>{{ old('body') }}</textarea>
                            @if($errors->has('body'))
                                <div class="text-danger mb-2" style="font-size:0.85rem;">{{ $errors->first('body') }}</div>
                            @endif
                            <button type="submit" class="fh-btn fh-btn-primary">
                                <i class="bi bi-send me-2"></i>Отправить
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="fh-card p-4 mt-4 text-center">
                        <i class="bi bi-lock-fill" style="font-size:2rem; color:var(--fh-text-muted);"></i>
                        <p class="text-muted mt-2 mb-0">Тред заблокирован. Новые ответы недоступны.</p>
                    </div>
                    @endif
                @else
                <div class="fh-card p-4 mt-4 text-center">
                    <p class="mb-3">Войдите, чтобы ответить</p>
                    <a href="{{ route('login') }}" class="fh-btn fh-btn-primary">Войти</a>
                </div>
                @endauth
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="fh-info-panel-light mb-4">
                    <h6>О треде</h6>
                    <div style="font-size:0.9rem;">
                        <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--fh-border);">
                            <span class="text-muted">Доска</span>
                            <span style="font-weight:500;">{{ $thread->board->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--fh-border);">
                            <span class="text-muted">Ответов</span>
                            <span style="font-weight:500;">{{ $thread->replies_count }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--fh-border);">
                            <span class="text-muted">Просмотров</span>
                            <span style="font-weight:500;">{{ $thread->views_count }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Создан</span>
                            <span style="font-weight:500;">{{ $thread->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="fh-info-panel-light">
                    <h6>Правила</h6>
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
            </div>
        </div>
    </div>
</section>
@endsection