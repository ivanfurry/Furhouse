@extends('layouts.app')

@section('title', 'Товары сообщества')

@section('content')
<!-- Page Header -->
<section class="fh-page-header">
    <div class="container">
        <span class="fh-section-label">МАРКЕТПЛЕЙС</span>
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="fh-section-title">Товары сообщества</h1>
                <p class="fh-section-subtitle">Авторские вещи, арт и детали образа — спокойно и с понятными условиями.</p>
            </div>
            @auth
                @if(auth()->user()->isMaster())
                <a href="{{ route('products.create') }}" class="fh-btn fh-btn-primary">
                    <i class="bi bi-plus me-2"></i>Разместить товар
                </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="fh-btn fh-btn-primary">
                    <i class="bi bi-plus me-2"></i>Разместить товар
                </a>
            @endauth
        </div>

        <!-- Search -->
        <div class="row g-3 mt-4">
            <div class="col-md-9">
                <form method="GET" action="{{ route('products.index') }}" class="d-flex gap-2">
                    <div class="fh-search flex-grow-1">
                        <i class="bi bi-search text-muted"></i>
                        <input type="text" name="search" placeholder="Название, автор или тег" value="{{ request('search') }}">
                    </div>
                    <select name="sort" class="fh-form-control" style="width:auto; min-width:180px;" onchange="this.form.submit()">
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Сначала популярные</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Новые</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Сначала дешёвые</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Сначала дорогие</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>По рейтингу</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="pb-5">
    <div class="container">
        <div class="row g-4">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                <div class="fh-filter-panel sticky-top" style="top:90px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Фильтры</h5>
                        <a href="{{ route('products.index') }}" class="text-decoration-none" style="font-size:0.85rem; color:var(--fh-primary);">Сбросить</a>
                    </div>

                    <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                        @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif

                        <!-- Categories -->
                        <div class="fh-filter-group">
                            <label>Категория</label>
                            @foreach($categories as $cat)
                            <label class="fh-checkbox-item">
                                <input type="checkbox" name="category[]" value="{{ $cat->id }}" 
                                    {{ in_array($cat->id, (array)request('category')) ? 'checked' : '' }}>
                                <span>{{ $cat->name }}</span>
                                <span class="count">{{ $cat->products_count ?? 0 }}</span>
                            </label>
                            @endforeach
                        </div>

                        <!-- Price -->
                        <div class="fh-filter-group">
                            <label>Цена</label>
                            <div class="fh-price-range">
                                <input type="number" name="price_min" placeholder="От" value="{{ request('price_min') }}" min="0">
                                <input type="number" name="price_max" placeholder="До" value="{{ request('price_max') }}" min="0">
                            </div>
                        </div>

                        <!-- Availability -->
                        <div class="fh-filter-group">
                            <label>Наличие</label>
                            <label class="fh-checkbox-item">
                                <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}>
                                <span>В наличии</span>
                                <span class="count">1 204</span>
                            </label>
                            <label class="fh-checkbox-item">
                                <input type="checkbox" name="preorder" value="1">
                                <span>Под заказ</span>
                                <span class="count">642</span>
                            </label>
                        </div>

                        <!-- Delivery -->
                        <div class="fh-filter-group">
                            <label>Доставка</label>
                            <label class="fh-checkbox-item">
                                <input type="checkbox" name="delivery" value="russia" {{ request('delivery') == 'russia' ? 'checked' : '' }}>
                                <span>По России</span>
                                <span class="count">1 576</span>
                            </label>
                            <label class="fh-checkbox-item">
                                <input type="checkbox" name="delivery" value="pickup" {{ request('delivery') == 'pickup' ? 'checked' : '' }}>
                                <span>Самовывоз</span>
                                <span class="count">284</span>
                            </label>
                            <label class="fh-checkbox-item">
                                <input type="checkbox" name="delivery" value="digital" {{ request('delivery') == 'digital' ? 'checked' : '' }}>
                                <span>Цифровой товар</span>
                                <span class="count">391</span>
                            </label>
                        </div>

                        <!-- Rating -->
                        <div class="fh-filter-group">
                            <label>Рейтинг</label>
                            <label class="fh-checkbox-item">
                                <input type="checkbox" name="rating" value="4" {{ request('rating') == '4' ? 'checked' : '' }}>
                                <span>4★ и выше</span>
                                <span class="count">982</span>
                            </label>
                            <label class="fh-checkbox-item">
                                <input type="checkbox" name="rating" value="5" {{ request('rating') == '5' ? 'checked' : '' }}>
                                <span>Только 5★</span>
                                <span class="count">416</span>
                            </label>
                        </div>

                        <button type="submit" class="fh-btn fh-btn-primary w-100">
                            Показать {{ $products->total() }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted" style="font-size:0.9rem;">{{ $products->total() }} товаров</span>
                    <div class="d-flex gap-2">
                        <span class="fh-badge fh-badge-success">В наличии</span>
                        <span class="fh-badge fh-badge-primary">4★ и выше</span>
                    </div>
                </div>

                <div class="row g-3">
                    @forelse($products as $product)
                    <div class="col-md-6 col-xl-4">
                        <a href="{{ route('products.show', $product) }}" class="fh-product-card d-block">
                            <div class="product-image-wrap">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/400x300/e8e0f0/7c5c9c?text=' . urlencode($product->title) }}" 
                                     alt="{{ $product->title }}">
                                <button class="favorite-btn" onclick="event.preventDefault(); toggleFavorite({{ $product->id }}, this)">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>
                            <div class="product-info">
                                <div class="product-title">{{ $product->title }}</div>
                                <div class="product-author">от {{ $product->user->name }}</div>
                                <div class="product-bottom">
                                    <div>
                                        <div class="product-price">{{ $product->formatted_price }}</div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="product-rating">
                                            <i class="bi bi-star-fill"></i> {{ number_format($product->rating, 1) }}
                                        </span>
                                    <button class="add-to-cart-btn" onclick="event.preventDefault(); addToCart({{ $product->id }}, this)" title="Добавить в корзину">
                                        <i class="bi bi-bag"></i>
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-inbox" style="font-size:3rem; color:var(--fh-border);"></i>
                        <p class="text-muted mt-3">Товары не найдены</p>
                        <a href="{{ route('products.index') }}" class="fh-btn fh-btn-outline">Сбросить фильтры</a>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    <nav>
                        <ul class="pagination fh-pagination">
                            {{ $products->links() }}
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection