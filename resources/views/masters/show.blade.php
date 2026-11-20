@extends('layouts.app')

@section('title', $service->title)

@section('content')
<section class="py-5">
    <div class="container">
        <nav class="masters-breadcrumb">
            <a href="{{ route('home') }}">Главная</a> / 
            <a href="{{ route('masters.index') }}">Мастера</a> / 
            {{ $service->title }}
        </nav>

        <div class="masters-layout">
            <!-- Main Content -->
            <div class="master-card">
                <div class="master-header">
                    <img src="{{ $service->user->avatar_url }}" class="master-avatar" alt="{{ $service->user->name }}">
                    <div>
                        <div class="master-name">
                            {{ $service->user->name }}
                            @if($service->user->is_verified)
                                <i class="bi bi-patch-check-fill verified-badge"></i>
                            @endif
                        </div>
                        @if($service->user->nickname)
                            <div class="master-nickname">{{ $service->user->nickname }}</div>
                        @endif
                    </div>
                </div>

                <span class="master-category-badge">{{ $service->category_label }}</span>

                <h1 class="master-title">{{ $service->title }}</h1>

                <div class="master-meta">
                    <div class="master-meta-item">
                        <i class="bi bi-star-fill" style="color:var(--fh-warning);"></i>
                        <span class="rating-value">{{ number_format($service->rating, 1) }}</span>
                        <span>({{ $service->reviews_count }} отзывов)</span>
                    </div>
                    <div class="master-meta-item">
                        <i class="bi bi-clock"></i>
                        <span>{{ $service->deadline }}</span>
                    </div>
                    <div class="master-meta-item">
                        <i class="bi bi-check-circle" style="color:var(--fh-success);"></i>
                        <span>{{ $service->completed_orders }} выполненных заказов</span>
                    </div>
                </div>

                <div class="master-description">
                    {{ $service->description }}
                </div>
            </div>

            <!-- Sidebar -->
            <div class="master-sidebar">
                <div class="sidebar-label">Ставка</div>
                <div class="sidebar-price">от {{ number_format($service->price, 0, '.', ' ') }} ₽</div>

                <div class="sidebar-info-box">
                    <div class="sidebar-info-row">
                        <span class="sidebar-info-label">Срок</span>
                        <span class="sidebar-info-value">{{ $service->deadline }}</span>
                    </div>
                    <div class="sidebar-info-row">
                        <span class="sidebar-info-label">Категория</span>
                        <span class="sidebar-info-value">{{ $service->category_label }}</span>
                    </div>
                    <div class="sidebar-info-row">
                        <span class="sidebar-info-label">Статус</span>
                        <span class="status-badge status-badge--{{ $service->accepts_orders ? 'active' : 'inactive' }}">
                            {{ $service->accepts_orders ? 'Принимает заказы' : 'Не принимает' }}
                        </span>
                    </div>
                </div>

                @auth
                    @if(auth()->id() !== $service->user_id)
                        <a href="{{ route('service-discussion.create', $service) }}" 
                        class="master-action-btn master-action-btn--primary">
                            <i class="bi bi-chat-dots"></i>Обсудить заказ
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="master-action-btn master-action-btn--primary">
                        <i class="bi bi-box-arrow-in-right"></i>Войти для обсуждения
                    </a>
                @endauth

                <button class="master-action-btn master-action-btn--outline" onclick="toggleFavorite({{ $service->id }}, 'service')">
                    <i class="bi bi-heart"></i>В избранное
                </button>

                <div class="master-about">
                    <div class="master-about-title">О мастере</div>
                    <div class="master-about-text">
                        {{ $service->user->bio ?? 'Проверенный мастер сообщества FurHouse.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection