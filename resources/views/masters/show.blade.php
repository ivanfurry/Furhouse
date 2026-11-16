@extends('layouts.app')

@section('title', $service->title)

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:0.9rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color:var(--fh-primary);">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('masters.index') }}" class="text-decoration-none" style="color:var(--fh-primary);">Мастера</a></li>
                <li class="breadcrumb-item active">{{ $service->title }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Service Info -->
            <div class="col-lg-8">
                <div class="fh-card p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ $service->user->avatar_url }}" class="fh-avatar fh-avatar-xl" alt="{{ $service->user->name }}">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h2 style="font-size:1.6rem; font-weight:600; margin:0;">{{ $service->user->name }}</h2>
                                @if($service->user->is_verified)
                                    <i class="bi bi-patch-check-fill text-primary" style="font-size:1.3rem;"></i>
                                @endif
                            </div>
                            <p class="text-muted mb-0">{{ $service->user->nickname ?? strtolower(str_replace(' ', '_', $service->user->name)) }}</p>
                        </div>
                    </div>

                    <span class="fh-badge fh-badge-primary mb-3">{{ $service->category_label }}</span>
                    <h1 style="font-size:2rem; font-weight:500; margin-bottom:16px;">{{ $service->title }}</h1>

                    <div class="d-flex flex-wrap gap-4 mb-4" style="font-size:0.95rem;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            <strong>{{ number_format($service->rating, 1) }}</strong>
                            <span class="text-muted">({{ $service->reviews_count }} отзывов)</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock text-muted"></i>
                            <span>{{ $service->deadline }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle text-success"></i>
                            <span>{{ $service->orders_count }} выполненных заказов</span>
                        </div>
                    </div>

                    <div style="line-height:1.8; font-size:1rem;">
                        {!! nl2br(e($service->description)) !!}
                    </div>

                    @if($portfolio->count() > 0)
                    <div class="mt-5">
                        <h3 style="font-size:1.3rem; font-weight:600; margin-bottom:20px;">Портфолио</h3>
                        <div class="row g-3">
                            @foreach($portfolio as $item)
                            <div class="col-md-4">
                                <a href="{{ route('products.show', $item) }}">
                                    <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/300x200/e8e0f0/7c5c9c' }}" 
                                         class="w-100" style="border-radius:12px; height:180px; object-fit:cover;" alt="{{ $item->title }}">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="fh-card p-4 sticky-top" style="top:90px;">
                    <div style="font-size:0.75rem; color:var(--fh-text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">Ставка</div>
                    <div style="font-size:2rem; font-weight:700; color:var(--fh-primary-dark); margin-bottom:20px;">{{ $service->formatted_price }}</div>

                    <div class="mb-4" style="padding:16px; background:var(--fh-bg); border-radius:12px;">
                        <div class="d-flex justify-content-between mb-2">
                            <span style="font-size:0.85rem; color:var(--fh-text-muted);">Срок</span>
                            <span style="font-weight:600;">{{ $service->deadline }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span style="font-size:0.85rem; color:var(--fh-text-muted);">Категория</span>
                            <span style="font-weight:600;">{{ $service->category_label }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="font-size:0.85rem; color:var(--fh-text-muted);">Статус</span>
                            <span class="fh-status-online">Принимает заказы</span>
                        </div>
                    </div>

                    @auth
                        <button class="fh-btn fh-btn-primary w-100 py-3 mb-3">
                            <i class="bi bi-chat-dots me-2"></i>Обсудить заказ
                        </button>
                        <button class="fh-btn fh-btn-ghost w-100 py-3">
                            <i class="bi bi-heart me-2"></i>В избранное
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="fh-btn fh-btn-primary w-100 py-3 text-center mb-3">
                            Войти для заказа
                        </a>
                    @endauth

                    <div class="mt-4 pt-4" style="border-top:1px solid var(--fh-border);">
                        <h6 style="font-size:0.85rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:var(--fh-text-muted); margin-bottom:12px;">О мастере</h6>
                        <p style="font-size:0.9rem; color:var(--fh-text-muted); margin:0;">
                            {{ $service->user->bio ?? 'Проверенный мастер сообщества FurHouse.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection