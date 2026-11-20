@extends('layouts.app')

@section('title', 'Оформление заказа')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="fh-section-title mb-4">Оформление заказа</h1>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="fh-card p-4 p-md-5">
                    <h3 style="font-size:1.3rem; font-weight:600; margin-bottom:20px;">Доставка</h3>
                    
                    <form method="POST" action="{{ route('cart.placeOrder') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Адрес доставки</label>
                            <textarea name="delivery_address" class="form-control fh-form-control" rows="3" placeholder="Город, улица, дом, квартира, индекс" required>{{ old('delivery_address') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Комментарий к заказу (опционально)</label>
                            <textarea name="comment" class="form-control fh-form-control" rows="2" placeholder="Пожелания по доставке, упаковке и т.д.">{{ old('comment') }}</textarea>
                        </div>

                        <button type="submit" class="fh-btn fh-btn-primary w-100 py-3">
                            <i class="bi bi-check-circle me-2"></i>Подтвердить заказ
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="fh-card p-4 sticky-top" style="top:90px;">
                    <h5 style="font-weight:600; margin-bottom:20px;">Ваш заказ</h5>
                    
                    @foreach($cartItems as $item)
                    <div class="d-flex gap-3 mb-3 pb-3" style="border-bottom:1px solid var(--fh-border);">
                        <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/60x60/e8e0f0/7c5c9c' }}" 
                             alt="{{ $item->product->title }}" 
                             style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                        <div class="flex-grow-1">
                            <div style="font-size:0.9rem; font-weight:500; margin-bottom:2px;">{{ $item->product->title }}</div>
                            <div style="font-size:0.8rem; color:var(--fh-text-muted);">{{ $item->quantity }} × {{ number_format($item->product->price, 0, '.', ' ') }} ₽</div>
                        </div>
                        <div style="font-weight:600; font-size:0.9rem;">{{ number_format($item->subtotal, 0, '.', ' ') }} ₽</div>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-between mt-3 pt-3" style="border-top:2px solid var(--fh-border);">
                        <span style="font-size:1.1rem; font-weight:600;">Итого:</span>
                        <span style="font-size:1.3rem; font-weight:700; color:var(--fh-primary-dark);">
                            {{ number_format($total, 0, '.', ' ') }} ₽
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection