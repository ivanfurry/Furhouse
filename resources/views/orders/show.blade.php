@extends('layouts.app')

@section('title', 'Заказ ' . $order->order_number)

@section('content')
<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:0.9rem;">
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none" style="color:var(--fh-primary);">Мои заказы</a></li>
                <li class="breadcrumb-item active">{{ $order->order_number }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="fh-card p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h2 style="font-size:1.6rem; font-weight:600; margin-bottom:4px;">Заказ {{ $order->order_number }}</h2>
                            <div style="font-size:0.9rem; color:var(--fh-text-muted);">
                                Оформлен {{ $order->created_at->format('d.m.Y H:i') }}
                            </div>
                        </div>
                        <span class="fh-badge" style="background:{{ $order->status_color }}20; color:{{ $order->status_color }}; font-size:0.9rem; padding:8px 16px;">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <h4 style="font-size:1.1rem; font-weight:600; margin-bottom:16px;">Состав заказа</h4>
                    
                    @foreach($order->items as $item)
                    <div class="d-flex gap-3 mb-3 pb-3" style="border-bottom:1px solid var(--fh-border);">
                        <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/80x80/e8e0f0/7c5c9c' }}" 
                             alt="{{ $item->product->title }}" 
                             style="width:80px; height:80px; object-fit:cover; border-radius:10px;">
                        <div class="flex-grow-1">
                            <div style="font-weight:600; margin-bottom:4px;">{{ $item->product->title }}</div>
                            <div style="font-size:0.85rem; color:var(--fh-text-muted); margin-bottom:4px;">от {{ $item->product->user->name }}</div>
                            <div style="font-size:0.9rem;">{{ $item->quantity }} × {{ number_format($item->price, 0, '.', ' ') }} ₽</div>
                        </div>
                        <div style="font-weight:700; font-size:1.1rem; color:var(--fh-primary-dark);">
                            {{ number_format($item->subtotal, 0, '.', ' ') }} ₽
                        </div>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-between align-items-center pt-3" style="border-top:2px solid var(--fh-border);">
                        <span style="font-size:1.2rem; font-weight:600;">Итого:</span>
                        <span style="font-size:1.5rem; font-weight:700; color:var(--fh-primary-dark);">
                            {{ number_format($order->total_amount, 0, '.', ' ') }} ₽
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="fh-card p-4 sticky-top" style="top:90px;">
                    <h5 style="font-weight:600; margin-bottom:16px;">Доставка</h5>
                    <p style="font-size:0.95rem; line-height:1.6; color:var(--fh-text);">
                        {{ $order->delivery_address }}
                    </p>

                    @if($order->comment)
                    <hr>
                    <h5 style="font-weight:600; margin-bottom:12px;">Комментарий</h5>
                    <p style="font-size:0.9rem; color:var(--fh-text-muted);">
                        {{ $order->comment }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection