@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="fh-auth-card">
            <div class="text-center mb-4">
                <div style="width:64px; height:64px; background:var(--fh-primary); border-radius:16px; display:inline-flex; align-items:center; justify-content:center; color:white; font-size:1.8rem; margin-bottom:16px;">
                    <i class="bi bi-house-heart"></i>
                </div>
                <h2 style="font-size:1.6rem; font-weight:500;">Добро пожаловать</h2>
                <p class="text-muted mb-0">Создайте аккаунт в FurHouse</p>
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

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Имя</label>
                    <input type="text" name="name" class="form-control fh-form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Никнейм <span class="text-muted">(опционально)</span></label>
                    <input type="text" name="nickname" class="form-control fh-form-control" value="{{ old('nickname') }}" placeholder="Например: VioletFox">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control fh-form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Город <span class="text-muted">(опционально)</span></label>
                    <input type="text" name="city" class="form-control fh-form-control" value="{{ old('city') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Пароль</label>
                    <input type="password" name="password" class="form-control fh-form-control" required>
                    <small class="text-muted">Минимум 8 символов, буквы и цифры</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Подтверждение пароля</label>
                    <input type="password" name="password_confirmation" class="form-control fh-form-control" required>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="agree_terms" id="agreeTerms" required>
                        <label class="form-check-label" for="agreeTerms" style="font-size:0.9rem;">
                            Я согласен с <a href="#" class="text-decoration-none" style="color:var(--fh-primary);">правилами сообщества</a>
                        </label>
                    </div>
                </div>

                <button type="submit" class="fh-btn fh-btn-primary w-100 py-3 mb-3">
                    Создать аккаунт
                </button>

                <div class="text-center" style="font-size:0.9rem;">
                    <span class="text-muted">Уже есть аккаунт?</span>
                    <a href="{{ route('login') }}" class="text-decoration-none" style="color:var(--fh-primary); font-weight:500;">Войти</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection