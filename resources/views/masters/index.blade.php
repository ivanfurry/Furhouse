@extends('layouts.app')

@section('title', 'Мастера')

@section('content')
<!-- Page Header -->
<section class="fh-page-header">
    <div class="container">
        <span class="fh-section-label">УСЛУГИ СОЗДАТЕЛЕЙ</span>
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="fh-section-title">Найдите мастера для своей идеи</h1>
                <p class="fh-section-subtitle">Портфолио, понятные ставки и сроки — всё важное видно сразу.</p>
            </div>
            @auth
                @if(auth()->user()->isMaster())
                <a href="{{ route('masters.create') }}" class="fh-btn fh-btn-primary">
                    <i class="bi bi-plus me-2"></i>Предложить услугу
                </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="fh-btn fh-btn-primary">
                    <i class="bi bi-plus me-2"></i>Предложить услугу
                </a>
            @endauth
        </div>

        <!-- Search & Filters -->
        <form method="GET" action="{{ route('masters.index') }}" class="row g-3 mt-4">
            <div class="col-md-9">
                <div class="fh-search">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text" name="search" placeholder="Услуга, навык или имя мастера" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="deadline" class="form-select fh-form-control" onchange="this.form.submit()">
                    <option value="">Любой срок</option>
                    <option value="дней" {{ request('deadline') == 'дней' ? 'selected' : '' }}>До 2 недель</option>
                    <option value="недель" {{ request('deadline') == 'недель' ? 'selected' : '' }}>До 2 месяцев</option>
                    <option value="месяц" {{ request('deadline') == 'месяц' ? 'selected' : '' }}>Более 2 месяцев</option>
                </select>
            </div>
        </form>

        <!-- Category Tags -->
        <div class="d-flex gap-2 mt-4 flex-wrap">
            <a href="{{ route('masters.index') }}" class="fh-btn {{ !request('category') ? 'fh-btn-primary' : 'fh-btn-ghost' }}" style="border-radius:20px;">
                Все
            </a>
            <a href="{{ route('masters.index', ['category' => 'art']) }}" class="fh-btn {{ request('category') == 'art' ? 'fh-btn-primary' : 'fh-btn-ghost' }}" style="border-radius:20px;">
                Иллюстрация
            </a>
            <a href="{{ route('masters.index', ['category' => 'costume']) }}" class="fh-btn {{ request('category') == 'costume' ? 'fh-btn-primary' : 'fh-btn-ghost' }}" style="border-radius:20px;">
                Костюмы
            </a>
            <a href="{{ route('masters.index', ['category' => '3d']) }}" class="fh-btn {{ request('category') == '3d' ? 'fh-btn-primary' : 'fh-btn-ghost' }}" style="border-radius:20px;">
                3D и VR
            </a>
            <a href="{{ route('masters.index', ['category' => 'music']) }}" class="fh-btn {{ request('category') == 'music' ? 'fh-btn-primary' : 'fh-btn-ghost' }}" style="border-radius:20px;">
                Музыка
            </a>
            <a href="{{ route('masters.index', ['category' => 'design']) }}" class="fh-btn {{ request('category') == 'design' ? 'fh-btn-primary' : 'fh-btn-ghost' }}" style="border-radius:20px;">
                Дизайн
            </a>
            <a href="{{ route('masters.index', ['category' => 'craft']) }}" class="fh-btn {{ request('category') == 'craft' ? 'fh-btn-primary' : 'fh-btn-ghost' }}" style="border-radius:20px;">
                Крафт
            </a>
        </div>

        <!-- Stats & Sort -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <span class="text-muted" style="font-size:0.9rem;">{{ $mastersCount }} мастеров открыты к заказам</span>
            <div class="dropdown">
                <button class="fh-btn fh-btn-ghost dropdown-toggle" data-bs-toggle="dropdown">
                    По рейтингу <i class="bi bi-chevron-down ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('masters.index', ['sort' => 'rating']) }}">По рейтингу</a></li>
                    <li><a class="dropdown-item" href="{{ route('masters.index', ['sort' => 'price_asc']) }}">Сначала дешёвые</a></li>
                    <li><a class="dropdown-item" href="{{ route('masters.index', ['sort' => 'price_desc']) }}">Сначала дорогие</a></li>
                    <li><a class="dropdown-item" href="{{ route('masters.index', ['sort' => 'newest']) }}">Новые</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Masters Grid -->
<section class="pb-5">
    <div class="container">
        <div class="row g-4">
            @forelse($services as $service)
            <div class="col-md-6 col-lg-4"> 
                <div class="fh-card p-4">
                    <!-- Master Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $service->user->avatar_url }}" class="fh-avatar fh-avatar-lg" alt="{{ $service->user->name }}">
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <strong style="font-size:1rem;">{{ $service->user->name }}</strong>
                                    @if($service->user->is_verified)
                                        <i class="bi bi-patch-check-fill text-primary" style="font-size:1rem;"></i>
                                    @endif
                                </div>
                                @if($service->user->nickname)
                                    <small class="text-muted">{{ '@' . $service->user->nickname }}</small>
                                @endif
                            </div>
                        </div>
                        <span class="fh-badge fh-badge-primary">{{ $service->category_label }}</span>
                    </div>

                    <hr class="my-3" style="border-color:var(--fh-border);">

                    <!-- Service Info -->
                    <h5 style="font-weight:600; margin-bottom:12px;">{{ $service->title }}</h5>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-1">
                            <i class="bi bi-star-fill text-warning"></i>
                            <strong>{{ number_format($service->rating, 1) }}</strong>
                            <span class="text-muted" style="font-size:0.85rem;">({{ $service->reviews_count }})</span>
                        </div>
                        <div class="text-muted" style="font-size:0.85rem;">
                            <i class="bi bi-clock me-1"></i>{{ $service->deadline }}
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size:0.75rem; color:var(--fh-text-muted); text-transform:uppercase; letter-spacing:0.5px;">Ставка</div>
                            <div style="font-size:1.2rem; font-weight:700; color:var(--fh-primary-dark);">{{ $service->formatted_price }}</div>
                        </div>
                        <a href="{{ route('masters.show', $service) }}" class="fh-btn fh-btn-outline">
                            <i class="bi bi-chat-dots me-2"></i>Обсудить
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-people" style="font-size:3rem; color:var(--fh-border);"></i>
                <p class="text-muted mt-3">Мастера не найдены</p>
                <a href="{{ route('masters.index') }}" class="fh-btn fh-btn-outline">Сбросить фильтры</a>
            </div>
            @endforelse
        </div>

        @if($services->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $services->links() }}
        </div>
        @endif
    </div>
</section>

<!-- CTA Banner -->
<section class="pb-5">
    <div class="container">
        <div class="fh-card p-4 p-md-5" style="background:rgba(124,92,156,0.08); border:none;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 style="font-weight:500; margin-bottom:8px;">Не знаете, с чего начать?</h3>
                    <p class="text-muted mb-0">Опишите идею — подходящие мастера откликнутся сами.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @auth
                        <button class="fh-btn fh-btn-primary">
                            <i class="bi bi-plus-square me-2"></i>Создать запрос
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="fh-btn fh-btn-primary">
                            <i class="bi bi-plus-square me-2"></i>Создать запрос
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
@endsection