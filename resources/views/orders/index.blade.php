@extends('layouts.app')

@section('title', 'Мои заказы')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="cart-page-title">Мои заказы</h1>

        @forelse($orders as $order)
        <div class="fh-card p-4 mb-3">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 style="font-weight:600; margin-bottom:4px;">Заказ {{ $order->order_number }}</h5>
                    <div style="font-size:0.85rem; color:var(--fh-text-muted);">
                        {{ $order->created_at->format('d.m.Y H:i') }}
                    </div>
                </div>
                <span class="fh-badge" style="background:{{ $order->status_color }}20; color:{{ $order->status_color }};">
                    {{ $order->status_label }}
                </span>
            </div>

            <div class="mb-3">
                @foreach($order->items as $item)
                <div class="d-flex gap-2 mb-2">
                    <span style="font-size:0.9rem;">{{ $item->product->title }}</span>
                    <span class="text-muted" style="font-size:0.85rem;">× {{ $item->quantity }}</span>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3" style="border-top:1px solid var(--fh-border);">
                <div style="font-size:1.2rem; font-weight:700; color:var(--fh-primary-dark);">
                    {{ number_format($order->total_amount, 0, '.', ' ') }} ₽
                </div>
                <a href="{{ route('orders.show', $order) }}" class="fh-btn fh-btn-outline">
                    Подробнее
                </a>
            </div>
        </div>
        @empty
        <div class="cart-empty">
            <div class="cart-empty-icon"><i class="bi bi-receipt"></i></div>
            <p class="cart-empty-text">У вас пока нет заказов</p>
            <a href="{{ route('products.index') }}" class="fh-btn fh-btn-primary">
                <i class="bi bi-bag me-2"></i>Перейти к покупкам
            </a>
        </div>
        @endforelse

        @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</section>
@endsection