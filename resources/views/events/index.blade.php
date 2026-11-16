@extends('layouts.app')

@section('title', 'Встречи')

@section('content')
<!-- Page Header -->
<section class="fh-page-header">
    <div class="container">
        <span class="fh-section-label">АФИША СООБЩЕСТВА</span>
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="fh-section-title">Встречайтесь спокойно</h1>
                <p class="fh-section-subtitle">Небольшие компании, творческие вечера и фестивали от проверенных организаторов.</p>
            </div>
            @auth
                @if(auth()->user()->isOrganizer())
                <a href="{{ route('events.create') }}" class="fh-btn fh-btn-primary">
                    <i class="bi bi-plus me-2"></i>Создать встречу
                </a>
                @endif
            @endauth
        </div>

        <!-- Search & Filters -->
        <form method="GET" action="{{ route('events.index') }}" class="row g-3 mt-4">
            <div class="col-md-6">
                <div class="fh-search">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text" name="search" placeholder="Событие, город или организатор" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="region" class="form-select fh-form-control">
                    <option value="">Регион</option>
                    <option value="Санкт-Петербург" {{ request('region') == 'Санкт-Петербург' ? 'selected' : '' }}>Санкт-Петербург</option>
                    <option value="Москва" {{ request('region') == 'Москва' ? 'selected' : '' }}>Москва</option>
                    <option value="Казань" {{ request('region') == 'Казань' ? 'selected' : '' }}>Казань</option>
                    <option value="Екатеринбург" {{ request('region') == 'Екатеринбург' ? 'selected' : '' }}>Екатеринбург</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select fh-form-control">
                    <option value="">Тип события</option>
                    <option value="walk" {{ request('type') == 'walk' ? 'selected' : '' }}>Прогулка</option>
                    <option value="picnic" {{ request('type') == 'picnic' ? 'selected' : '' }}>Пикник</option>
                    <option value="creative" {{ request('type') == 'creative' ? 'selected' : '' }}>Творчество</option>
                    <option value="games" {{ request('type') == 'games' ? 'selected' : '' }}>Игры</option>
                    <option value="festival" {{ request('type') == 'festival' ? 'selected' : '' }}>Фестиваль</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="group_size" class="form-select fh-form-control">
                    <option value="">Размер группы</option>
                    <option value="10" {{ request('group_size') == '10' ? 'selected' : '' }}>До 10</option>
                    <option value="20" {{ request('group_size') == '20' ? 'selected' : '' }}>До 20</option>
                    <option value="50" {{ request('group_size') == '50' ? 'selected' : '' }}>До 50</option>
                </select>
            </div>
        </form>

        <!-- Trust badges -->
        <div class="row g-3 mt-4">
            <div class="col-md-3">
                <div class="fh-card p-3 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-check text-success"></i>
                    <span style="font-size:0.9rem;">Проверенные организаторы</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="fh-card p-3 d-flex align-items-center gap-2">
                    <i class="bi bi-people text-primary"></i>
                    <span style="font-size:0.9rem;">Понятный размер группы</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="fh-card p-3 d-flex align-items-center gap-2">
                    <i class="bi bi-geo-alt text-info"></i>
                    <span style="font-size:0.9rem;">Открытое место встречи</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="fh-card p-3 d-flex align-items-center gap-2">
                    <i class="bi bi-heart text-danger"></i>
                    <span style="font-size:0.9rem;">Кодекс уважения</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Event -->
@if($featuredEvent)
<section class="pb-5">
    <div class="container">
        <div class="fh-featured-event">
            <div class="row g-0 align-items-center">
                <div class="col-md-5">
                    <img src="{{ $featuredEvent->image ? asset('storage/' . $featuredEvent->image) : 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=600&h=400&fit=crop' }}" 
                         class="event-image w-100" alt="{{ $featuredEvent->title }}">
                </div>
                <div class="col-md-7">
                    <div class="event-info">
                        <span class="fh-badge" style="background:rgba(255,255,255,0.2); color:white; margin-bottom:12px;">ВЫБОР НЕДЕЛИ</span>
                        <span class="fh-badge fh-badge-success ms-2" style="background:rgba(107,158,122,0.3); color:#a8d5b5;">{{ $featuredEvent->type_label }}</span>
                        <h3 class="mt-3">{{ $featuredEvent->title }}</h3>
                        <p style="opacity:0.9; font-size:0.95rem;">
                            <i class="bi bi-calendar me-2"></i>{{ $featuredEvent->formatted_date }} · 
                            <i class="bi bi-geo-alt ms-2 me-1"></i>{{ $featuredEvent->city }}
                        </p>
                        <div class="d-flex gap-4 mt-3" style="font-size:0.9rem; opacity:0.9;">
                            <span><i class="bi bi-people me-1"></i> до {{ $featuredEvent->max_guests ?? '∞' }} гостей</span>
                            <span><i class="bi bi-tag me-1"></i> {{ $featuredEvent->is_free ? 'Бесплатно' : $featuredEvent->price . ' ₽' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <img src="{{ $featuredEvent->user->avatar_url }}" class="fh-avatar" alt="">
                            <div>
                                <div style="font-size:0.75rem; opacity:0.7;">ОРГАНИЗАТОР</div>
                                <div style="font-weight:600;">{{ $featuredEvent->user->display_name }}</div>
                            </div>
                        </div>
                        <a href="{{ route('events.show', $featuredEvent) }}" class="fh-btn fh-btn-outline mt-4" style="background:white; color:var(--fh-primary-dark); border:none;">
                            Подробнее <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Events Grid -->
<section class="pb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fh-section-title" style="font-size:1.5rem;">Ближайшие встречи</h2>
            <span class="text-muted" style="font-size:0.9rem;">{{ $events->total() }} события</span>
        </div>

        <div class="row g-4">
            @forelse($events as $event)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('events.show', $event) }}" class="fh-event-card d-block">
                    <div class="event-image-wrap">
                        <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=400&h=300&fit=crop' }}" 
                             alt="{{ $event->title }}">
                        <div class="event-date-badge">{{ $event->starts_at->format('d M, H:i') }}</div>
                    </div>
                    <div class="event-info">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fh-badge fh-badge-primary">{{ $event->type_label }}</span>
                            @if($event->is_verified)
                                <span class="fh-badge fh-badge-success"><i class="bi bi-shield-check me-1"></i>Проверено</span>
                            @endif
                        </div>
                        <div class="event-title">{{ $event->title }}</div>
                        <div class="event-meta">
                            <i class="bi bi-geo-alt"></i> {{ $event->city }}
                        </div>
                        <div class="event-bottom">
                            <div class="event-meta mb-0">
                                <i class="bi bi-people"></i> до {{ $event->max_guests ?? '∞' }} гостей
                            </div>
                            <div style="font-weight:700; color:var(--fh-primary-dark);">
                                {{ $event->is_free ? 'Бесплатно' : number_format($event->price, 0, '.', ' ') . ' ₽' }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-calendar-x" style="font-size:3rem; color:var(--fh-border);"></i>
                <p class="text-muted mt-3">Встречи не найдены</p>
            </div>
            @endforelse
        </div>

        @if($events->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $events->links() }}
        </div>
        @endif
    </div>
</section>
@endsection