@extends('layouts.app')

@section('title', $product->title)

@section('content')
<section class="py-5">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:0.9rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color:var(--fh-primary);">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none" style="color:var(--fh-primary);">Товары</a></li>
                <li class="breadcrumb-item active">{{ $product->title }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Image -->
            <div class="col-lg-7">
                <div class="fh-card">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/800x600/e8e0f0/7c5c9c?text=' . urlencode($product->title) }}" 
                         alt="{{ $product->title }}" 
                         style="width:100%; border-radius:var(--fh-radius);">
                </div>
            </div>

            <!-- Info -->
            <div class="col-lg-5">
                <span class="fh-badge fh-badge-primary mb-3">{{ $product->category->name ?? 'Категория' }}</span>
                <h1 style="font-size:2rem; font-weight:500; margin-bottom:12px;">{{ $product->title }}</h1>
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ $product->user->avatar_url }}" class="fh-avatar" alt="">
                        <div>
                            <div style="font-weight:600; font-size:0.95rem;">{{ $product->user->display_name }}</div>
                            <div style="font-size:0.8rem; color:var(--fh-text-muted);">
                                @if($product->user->is_verified)
                                    <i class="bi bi-shield-check text-success"></i> Проверенный мастер
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mb-4">
                    <span style="font-size:2rem; font-weight:700; color:var(--fh-primary-dark);">{{ $product->formatted_price }}</span>
                    <div class="product-rating ms-2" style="font-size:1rem;">
                        <i class="bi bi-star-fill text-warning"></i> 
                        <strong>{{ number_format($product->rating, 1) }}</strong>
                        <span class="text-muted">({{ $product->reviews_count }} отзывов)</span>
                    </div>
                </div>

                <div class="mb-4" style="line-height:1.7; color:var(--fh-text-muted);">
                    {!! nl2br(e($product->description)) !!}
                </div>

                <div class="d-flex gap-2 mb-4">
                    @if($product->in_stock)
                        <span class="fh-badge fh-badge-success"><i class="bi bi-check-circle me-1"></i> В наличии</span>
                    @else
                        <span class="fh-badge fh-badge-warning"><i class="bi bi-clock me-1"></i> Под заказ</span>
                    @endif
                    @if($product->delivery_type)
                        <span class="fh-badge fh-badge-primary">
                            <i class="bi bi-truck me-1"></i>
                            {{ $product->delivery_type === 'russia' ? 'По России' : ($product->delivery_type === 'pickup' ? 'Самовывоз' : 'Цифровой') }}
                        </span>
                    @endif
                </div>

                <div class="d-flex gap-3">
                    <button class="fh-btn fh-btn-primary flex-grow-1 py-3" style="font-size:1rem;">
                        <i class="bi bi-bag me-2"></i>Добавить в корзину
                    </button>
                    <button class="fh-btn fh-btn-ghost fh-btn-icon" style="width:52px; height:52px;" onclick="toggleFavorite({{ $product->id }})">
                        <i class="bi bi-heart"></i>
                    </button>
                </div>

                <!-- Stats -->
                <div class="row g-3 mt-4">
                    <div class="col-4">
                        <div class="text-center">
                            <div style="font-size:1.3rem; font-weight:700;">{{ $product->views_count }}</div>
                            <div style="font-size:0.8rem; color:var(--fh-text-muted);">Просмотров</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div style="font-size:1.3rem; font-weight:700;">{{ $product->favorites_count }}</div>
                            <div style="font-size:0.8rem; color:var(--fh-text-muted);">В избранном</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div style="font-size:1.3rem; font-weight:700;">{{ $product->reviews_count }}</div>
                            <div style="font-size:0.8rem; color:var(--fh-text-muted);">Отзывов</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        @if($product->reviews->count() > 0)
        <div class="mt-5">
            <h3 style="font-size:1.4rem; font-weight:600; margin-bottom:24px;">Отзывы</h3>
            <div class="row g-3">
                @foreach($product->reviews as $review)
                <div class="col-md-6">
                    <div class="fh-card p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ $review->user->avatar_url }}" class="fh-avatar" alt="">
                            <div>
                                <div style="font-weight:600;">{{ $review->user->name }}</div>
                                <div style="font-size:0.8rem; color:var(--text-muted);">
                                    <i class="bi bi-star-fill text-warning"></i> {{ $review->rating }} · {{ $review->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        <p class="mb-0" style="font-size:0.9rem;">{{ $review->comment }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h3 style="font-size:1.4rem; font-weight:600; margin-bottom:24px;">Похожие товары</h3>
            <div class="row g-3">
                @foreach($relatedProducts as $related)
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('products.show', $related) }}" class="fh-product-card d-block">
                        <div class="product-image-wrap" style="height:180px;">
                            <img src="{{ $related->image ? asset('storage/' . $related->image) : 'https://via.placeholder.com/400x300/e8e0f0/7c5c9c' }}" 
                                 alt="{{ $related->title }}">
                        </div>
                        <div class="product-info">
                            <div class="product-title" style="font-size:0.9rem;">{{ $related->title }}</div>
                            <div class="product-price">{{ $related->formatted_price }}</div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
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
        alert(data.favorited ? 'Добавлено в избранное' : 'Удалено из избранного');
    });
}
</script>
@endpush