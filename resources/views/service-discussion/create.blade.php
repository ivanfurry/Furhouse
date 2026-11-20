@extends('layouts.app')

@section('title', 'Обсуждение услуги')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fh-card p-4 p-md-5">
                    <h2 class="fh-section-title" style="font-size:1.6rem; margin-bottom:8px;">Обсуждение услуги</h2>
                    <p class="text-muted mb-4">Отправьте заявку мастеру для обсуждения деталей заказа</p>

                    <!-- Информация об услуге -->
                    <div class="p-4 mb-4" style="background:var(--fh-bg); border-radius:12px;">
                        <div class="d-flex gap-3">
                            <img src="{{ $service->image ? asset('storage/' . $service->image) : 'https://via.placeholder.com/100x100/e8e0f0/7c5c9c' }}" 
                                 alt="{{ $service->title }}" 
                                 style="width:100px; height:100px; object-fit:cover; border-radius:10px;">
                            <div class="flex-grow-1">
                                <h5 style="font-weight:600; margin-bottom:4px;">{{ $service->title }}</h5>
                                <p class="text-muted mb-2" style="font-size:0.9rem;">от {{ $service->user->display_name }}</p>
                                <div style="font-size:1.1rem; font-weight:700; color:var(--fh-primary-dark);">
                                    от {{ number_format($service->price, 0, '.', ' ') }} ₽
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                    <div class="alert alert-danger fh-alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('service-discussion.store', $service) }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Ваше сообщение мастеру</label>
                            <textarea name="message" class="form-control fh-form-control" rows="6" placeholder="Опишите, что вы хотите заказать, сроки, бюджет и другие детали..." required>{{ old('message') }}</textarea>
                            <small class="text-muted">Минимум 10 символов</small>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="fh-btn fh-btn-primary px-4 py-2">
                                <i class="bi bi-send me-2"></i>Отправить заявку
                            </button>
                            <a href="{{ route('masters.show', $service) }}" class="fh-btn fh-btn-ghost px-4 py-2">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection