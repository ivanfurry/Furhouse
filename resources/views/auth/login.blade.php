@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="fh-auth-card">
            <div class="text-center mb-4">
                <div style="width:64px; height:64px; background:var(--fh-primary); border-radius:16px; display:inline-flex; align-items:center; justify-content:center; color:white; font-size:1.8rem; margin-bottom:16px;">
                    <i class="bi bi-house-heart"></i>
                </div>
                <h2 style="font-size:1.6rem; font-weight:500;">С возвращением</h2>
                <p class="text-muted mb-0">Войдите в свой аккаунт FurHouse</p>
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

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control fh-form-control" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Пароль</label>
                    <input type="password" name="password" class="form-control fh-form-control" required>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember" style="font-size:0.9rem;">Запомнить меня</label>
                    </div>
                    <a href="#" class="text-decoration-none" style="font-size:0.9rem; color:var(--fh-primary);">Забыли пароль?</a>
                </div>

                <button type="submit" class="fh-btn fh-btn-primary w-100 py-3 mb-3">
                    Войти
                </button>

                <div class="text-center" style="font-size:0.9rem;">
                    <span class="text-muted">Нет аккаунта?</span>
                    <a href="{{ route('register') }}" class="text-decoration-none" style="color:var(--fh-primary); font-weight:500;">Зарегистрироваться</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection