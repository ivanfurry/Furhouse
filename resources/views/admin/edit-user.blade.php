@extends('layouts.app')

@section('title', 'Редактировать пользователя')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fh-card p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ $user->avatar_url }}" class="fh-avatar fh-avatar-xl" alt="">
                        <div>
                            <h2 style="font-size:1.4rem; font-weight:600; margin:0;">{{ $user->display_name }}</h2>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
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

                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
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
                                <label class="form-label fw-semibold">Роль</label>
                                <select name="role" class="form-select fh-form-control" required>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Пользователь</option>
                                    <option value="master" {{ $user->role == 'master' ? 'selected' : '' }}>Мастер</option>
                                    <option value="organizer" {{ $user->role == 'organizer' ? 'selected' : '' }}>Организатор</option>
                                    <option value="moderator" {{ $user->role == 'moderator' ? 'selected' : '' }}>Модератор</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Администратор</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_verified" value="1" id="isVerified" {{ $user->is_verified ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isVerified">Проверенный пользователь</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ $user->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Активен</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="fh-btn fh-btn-primary px-4 py-2">
                                <i class="bi bi-check me-2"></i>Сохранить
                            </button>
                            <a href="{{ route('admin.users') }}" class="fh-btn fh-btn-ghost px-4 py-2">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection