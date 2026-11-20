@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="cart-page-title">Корзина</h1>

        @if($cartItems->isEmpty())
        <div class="cart-empty">
            <div class="cart-empty-icon"><i class="bi bi-bag"></i></div>
            <p class="cart-empty-text">Ваша корзина пуста</p>
            <a href="{{ route('products.index') }}" class="fh-btn fh-btn-primary">
                <i class="bi bi-bag me-2"></i>Перейти к покупкам
            </a>
        </div>
        @else
        <div class="cart-layout">
            <!-- Cart Items -->
            <div class="cart-items-list">
                @foreach($cartItems as $item)
                <div class="cart-item">
                    <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/140x140/e8e0f0/7c5c9c' }}" 
                         alt="{{ $item->product->title }}" 
                         class="cart-item-image">
                    
                    <div class="cart-item-info">
                        <div>
                            <div class="cart-item-title">{{ $item->product->title }}</div>
                            <div class="cart-item-author">от {{ $item->product->user->name }}</div>
                        </div>
                        
                        <div class="cart-item-bottom">
                            <form method="POST" action="{{ route('cart.update', $item) }}" class="cart-quantity">
                                @csrf
                                @method('PUT')
                                <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <div class="quantity-value">{{ $item->quantity }}</div>
                                <button type="submit" name="quantity" value="{{ min(99, $item->quantity + 1) }}">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </form>
                            
                            <div class="text-end">
                                <div class="cart-item-price">{{ number_format($item->subtotal, 0, '.', ' ') }} ₽</div>
                                <form method="POST" action="{{ route('cart.remove', $item) }}" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cart-item-remove" onclick="return confirm('Удалить товар?')">
                                        <i class="bi bi-trash"></i> Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <div class="cart-summary-title">Итого</div>
                
                <div class="cart-summary-row">
                    <span class="label">Товаров:</span>
                    <span class="value">{{ $cartItems->sum('quantity') }}</span>
                </div>
                
                <div class="cart-summary-divider"></div>
                
                <div class="cart-summary-total">
                    <span class="label">Сумма:</span>
                    <span class="value">{{ number_format($total, 0, '.', ' ') }} ₽</span>
                </div>
                
                <div class="cart-summary-actions">
                    <a href="{{ route('cart.checkout') }}" class="fh-btn fh-btn-primary">
                        <i class="bi bi-credit-card me-2"></i>Оформить заказ
                    </a>
                    <a href="{{ route('products.index') }}" class="fh-btn fh-btn-outline">
                        Продолжить покупки
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection