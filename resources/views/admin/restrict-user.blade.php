@extends('layouts.app')

@section('title', 'Ограничения пользователя')

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

                    <form method="POST" action="{{ route('admin.users.restrict.store', $user) }}">
                        @csrf

                        <h5 style="font-weight:600; margin-bottom:16px;">Ограничения возможностей</h5>
                        <p class="text-muted" style="font-size:0.9rem; margin-bottom:20px;">
                            Отметьте галочками те возможности, которые <strong>останутся доступны</strong> пользователю. Снятые галочки = запрет.
                        </p>
                        
                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="can_post_products" value="1" id="canPostProducts" {{ $user->can_post_products ? 'checked' : '' }}>
                                <label class="form-check-label" for="canPostProducts">
                                    Размещение товаров
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="can_post_services" value="1" id="canPostServices" {{ $user->can_post_services ? 'checked' : '' }}>
                                <label class="form-check-label" for="canPostServices">
                                    Размещение услуг
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="can_create_events" value="1" id="canCreateEvents" {{ $user->can_create_events ? 'checked' : '' }}>
                                <label class="form-check-label" for="canCreateEvents">
                                    Создание встреч
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="can_post_forum" value="1" id="canPostForum" {{ $user->can_post_forum ? 'checked' : '' }}>
                                <label class="form-check-label" for="canPostForum">
                                    Публикация в форуме
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="can_comment" value="1" id="canComment" {{ $user->can_comment ? 'checked' : '' }}>
                                <label class="form-check-label" for="canComment">
                                    Комментирование
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Причина ограничения</label>
                            <textarea name="restriction_reason" class="form-control fh-form-control" rows="3" placeholder="Опишите нарушение правил..." required>{{ old('restriction_reason', $user->restriction_reason) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Срок ограничения (опционально)</label>
                            <input type="datetime-local" name="restriction_until" class="form-control fh-form-control" value="{{ old('restriction_until', $user->restriction_until ? \Carbon\Carbon::parse($user->restriction_until)->format('Y-m-d\TH:i') : '') }}">
                            <small class="text-muted">Оставьте пустым для бессрочного ограничения</small>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="fh-btn fh-btn-primary px-4 py-2">
                                <i class="bi bi-shield-x me-2"></i>Применить ограничения
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