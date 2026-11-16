@extends('layouts.app')

@section('title', 'Профиль')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Profile Info -->
            <div class="col-lg-4">
                <div class="fh-card p-4 text-center">
                    <img src="{{ $user->avatar_url }}" class="fh-avatar fh-avatar-xl mx-auto mb-3" alt="">
                    <h4 style="font-weight:600;">{{ $user->display_name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    @if($user->city)
                        <span class="fh-badge fh-badge-primary mb-3"><i class="bi bi-geo-alt me-1"></i>{{ $user->city }}</span>
                    @endif
                    @if($user->is_verified)
                        <span class="fh-badge fh-badge-success"><i class="bi bi-shield-check me-1"></i>Проверен</span>
                    @endif
                    <div class="mt-3" style="font-size:0.9rem; color:var(--fh-text-muted);">
                        {{ $user->bio ?? 'Расскажите о себе...' }}
                    </div>
                </div>
            </div>

            <!-- Edit Profile -->
            <div class="col-lg-8">
                <div class="fh-card p-4 mb-4">
                    <h4 style="font-weight:600; margin-bottom:20px;">Редактировать профиль</h4>
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Имя</label>
                                <input type="text" name="name" class="form-control fh-form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Никнейм</label>
                                <input type="text" name="nickname" class="form-control fh-form-control" value="{{ old('nickname', $user->nickname) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control fh-form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Город</label>
                                <input type="text" name="city" class="form-control fh-form-control" value="{{ old('city', $user->city) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">О себе</label>
                                <textarea name="bio" class="form-control fh-form-control" rows="3">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Аватар</label>
                                <input type="file" name="avatar" class="form-control fh-form-control" accept="image/*">
                            </div>
                        </div>

                        <button type="submit" class="fh-btn fh-btn-primary mt-4">
                            <i class="bi bi-check me-2"></i>Сохранить
                        </button>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="fh-card p-4">
                    <h4 style="font-weight:600; margin-bottom:20px;">Сменить пароль</h4>
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Текущий пароль</label>
                            <input type="password" name="current_password" class="form-control fh-form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Новый пароль</label>
                            <input type="password" name="password" class="form-control fh-form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Подтверждение</label>
                            <input type="password" name="password_confirmation" class="form-control fh-form-control" required>
                        </div>

                        <button type="submit" class="fh-btn fh-btn-primary">
                            <i class="bi bi-key me-2"></i>Сменить пароль
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection