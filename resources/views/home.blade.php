@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<!-- Hero Section -->
<section class="fh-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="fh-badge fh-badge-primary mb-3">МАРКЕТПЛЕЙС СООБЩЕСТВА</span>
                <h1 class="fh-section-title" style="font-size:2.8rem; font-weight:300; line-height:1.1;">
                    Творчество<br>находит свой дом
                </h1>
                <p class="fh-section-subtitle mb-4" style="font-size:1.05rem;">
                    Покупайте работы, находите мастеров и собирайтесь на добрые встречи.
                </p>
                <div class="d-flex gap-3 flex-wrap mb-4">
                    <a href="{{ route('products.index') }}" class="fh-btn fh-btn-primary px-4 py-2">
                        <i class="bi bi-bag me-2"></i>Открыть каталог
                    </a>
                    <a href="{{ route('products.index') }}" class="fh-btn fh-btn-outline px-4 py-2">
                        <i class="bi bi-search me-2"></i>Найти мастера
                    </a>
                </div>
                <div class="d-flex gap-4 flex-wrap" style="font-size:0.9rem; color:var(--fh-text-muted);">
                    <span><i class="bi bi-people me-1"></i> 12 800 участников</span>
                    <span>·</span>
                    <span><i class="bi bi-shield-check me-1"></i> Безопасные сделки</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="fh-hero-image">
                    <img src="{{ asset('images/hero-banner.jpg')}}" 
                         alt="Творческая мастерская" 
                         style="width:100%; border-radius:var(--fh-radius);">
                    <div class="position-absolute bottom-0 end-0 m-3">
                        <span class="fh-badge fh-badge-success" style="background:white; color:var(--fh-success);">
                            <i class="bi bi-shield-check me-1"></i> Проверенные авторы
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fh-section-title" style="font-size:1.6rem;">Найдите своё</h2>
                <p class="text-muted mb-0" style="font-size:0.9rem;">От маленьких деталей до большого образа</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-decoration-none" style="color:var(--fh-primary); font-weight:500; font-size:0.9rem;">
                Все категории <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-3">
            @forelse($categories as $category)
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="fh-category-card d-block">
                    <div class="cat-icon" style="background:rgba(124,92,156,0.1); color:var(--fh-primary);">
                        <i class="bi bi-{{ $category->icon ?? 'palette' }}"></i>
                    </div>
                    <h6>{{ $category->name }}</h6>
                    <small>{{ $category->products_count ?? 0 }} работ</small>
                </a>
            </div>
            @empty
            <div class="col-12">
                <div class="fh-category-card">
                    <div class="cat-icon" style="background:rgba(124,92,156,0.1); color:var(--fh-primary);">
                        <i class="bi bi-palette"></i>
                    </div>
                    <h6>Арт и дизайн</h6>
                    <small>1 240 работ</small>
                </div>
            </div>
            <div class="col-12">
                <div class="fh-category-card">
                    <div class="cat-icon" style="background:rgba(212,168,83,0.15); color:#a07820;">
                        <i class="bi bi-scissors"></i>
                    </div>
                    <h6>Костюмы</h6>
                    <small>420 товаров</small>
                </div>
            </div>
            <div class="col-12">
                <div class="fh-category-card">
                    <div class="cat-icon" style="background:rgba(107,158,122,0.12); color:var(--fh-success);">
                        <i class="bi bi-gem"></i>
                    </div>
                    <h6>Аксессуары</h6>
                    <small>860 находок</small>
                </div>
            </div>
            <div class="col-12">
                <div class="fh-category-card">
                    <div class="cat-icon" style="background:rgba(196,92,92,0.12); color:var(--fh-danger);">
                        <i class="bi bi-stars"></i>
                    </div>
                    <h6>Стикеры</h6>
                    <small>510 наборов</small>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5" style="background:var(--fh-bg-light);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fh-section-title" style="font-size:1.6rem;">Сейчас выбирают</h2>
                <p class="text-muted mb-0" style="font-size:0.9rem;">Новые и любимые работы авторов</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-decoration-none" style="color:var(--fh-primary); font-weight:500; font-size:0.9rem;">
                Смотреть каталог <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($featuredProducts as $product)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('products.show', $product) }}" class="fh-product-card d-block">
                    <div class="product-image-wrap">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : '' . urlencode($product->title) }}" 
                             alt="{{ $product->title }}">
                        <button class="favorite-btn" onclick="event.preventDefault(); toggleFavorite({{ $product->id }})">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    <div class="product-info">
                        <div class="product-title">{{ $product->title }}</div>
                        <div class="product-author">от {{ $product->user->name }}</div>
                        <div class="product-bottom">
                            <div>
                                <div class="product-price">{{ $product->formatted_price }}</div>
                                <div class="product-rating">
                                    <i class="bi bi-star-fill"></i> {{ number_format($product->rating, 1) }} ({{ $product->reviews_count }})
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Товары скоро появятся</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Promo Banners -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="fh-promo-card dark">
                    <span class="fh-badge" style="background:rgba(255,255,255,0.2); color:white; margin-bottom:12px; display:inline-flex;">ДЛЯ ВАШЕЙ ИДЕИ</span>
                    <h4>Закажите работу у мастера</h4>
                    <p>Коммишены, костюмы, музыка и многое другое.</p>
                    <a href="{{ route('masters.index') }}" class="fh-btn fh-btn-outline" style="background:white; color:var(--fh-primary-dark); border:none; display:inline-flex; width:auto;">
                        Выбрать мастера
                    </a>
                    <img src="{{ asset('../images/master_redpanda.jpg')}}" 
                         class="promo-image d-none d-md-block" alt="">
                </div>
            </div>
            <div class="col-md-6">
                <div class="fh-promo-card light">
                    <span class="fh-badge" style="background:rgba(107,158,122,0.2); color:var(--fh-success); margin-bottom:12px; display:inline-flex;">РЯДОМ С ВАМИ</span>
                    <h4 style="color:var(--fh-text);">Присоединяйтесь к встрече</h4>
                    <p style="color:var(--fh-text-muted);">Камерные прогулки и большие фестивали.</p>
                    <a href="{{ route('events.index') }}" class="fh-btn fh-btn-primary" style="display:inline-flex; width:auto;">
                        Открыть афишу
                    </a>
                    <img src="{{ asset('../images/picnic-event.jpg') }}" 
                         class="promo-image d-none d-md-block" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Section -->
<section class="py-5" style="background:var(--fh-bg-light);">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="fh-trust-item">
                    <div class="trust-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <h6>Спокойно</h6>
                        <small>Модерация и прозрачные правила</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="fh-trust-item">
                    <div class="trust-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <div>
                        <h6>По-доброму</h6>
                        <small>Уважение к авторам и участникам</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="fh-trust-item">
                    <div class="trust-icon">
                        <i class="bi bi-stars"></i>
                    </div>
                    <div>
                        <h6>С интересом</h6>
                        <small>Новые работы и события каждый день</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleFavorite(productId) {
    fetch(`/products/${productId}/favorite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Visual feedback
        const btn = event.currentTarget;
        const icon = btn.querySelector('i');
        if (data.favorited) {
            icon.classList.remove('bi-heart');
            icon.classList.add('bi-heart-fill');
            btn.classList.add('active');
        } else {
            icon.classList.remove('bi-heart-fill');
            icon.classList.add('bi-heart');
            btn.classList.remove('active');
        }
    });
}
</script>
@endpush