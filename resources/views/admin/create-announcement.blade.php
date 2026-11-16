@extends('layouts.app')

@section('title', 'Новое объявление')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="announcements-breadcrumb">
            <a href="{{ route('admin.announcements') }}">Объявления</a> / Новое объявление
        </div>

        <div class="announcement-form-card">
            <h2 class="announcement-form-title">Новое объявление команде</h2>
            <p class="announcement-form-subtitle">Объявление будет видно всем администраторам и модераторам</p>

            @if($errors->any())
            <div class="alert alert-danger fh-alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.announcements.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Заголовок</label>
                    <input type="text" name="title" class="form-control fh-form-control" value="{{ old('title') }}" placeholder="Кратко и по делу" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Текст объявления</label>
                    <textarea name="body" class="form-control fh-form-control" rows="6" placeholder="Подробно опишите суть объявления..." required>{{ old('body') }}</textarea>
                    <small class="text-muted">Минимум 10 символов</small>
                </div>

                <div class="announcement-form-row mb-3">
                    <div>
                        <label class="form-label fw-semibold mb-2">Приоритет</label>
                        <div class="priority-selector">
                            <div class="priority-option priority-option--normal">
                                <input type="radio" name="priority" id="priorityNormal" value="normal" {{ old('priority', 'normal') == 'normal' ? 'checked' : '' }}>
                                <label for="priorityNormal">Обычное</label>
                            </div>
                            <div class="priority-option priority-option--important">
                                <input type="radio" name="priority" id="priorityImportant" value="important" {{ old('priority') == 'important' ? 'checked' : '' }}>
                                <label for="priorityImportant">Важное</label>
                            </div>
                            <div class="priority-option priority-option--urgent">
                                <input type="radio" name="priority" id="priorityUrgent" value="urgent" {{ old('priority') == 'urgent' ? 'checked' : '' }}>
                                <label for="priorityUrgent">Срочное</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-semibold">Срок действия</label>
                        <input type="datetime-local" name="expires_at" class="form-control fh-form-control" value="{{ old('expires_at') }}">
                        <small class="text-muted">Оставьте пустым для бессрочного</small>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_pinned" value="1" id="isPinned" {{ old('is_pinned') ? 'checked' : '' }}>
                    <label class="form-check-label" for="isPinned">
                        <i class="bi bi-pin-fill me-1"></i>Закрепить объявление сверху списка
                    </label>
                </div>

                <div class="announcement-form-actions">
                    <button type="submit" class="fh-btn fh-btn-primary px-4 py-2">
                        <i class="bi bi-megaphone me-2"></i>Опубликовать
                    </button>
                    <a href="{{ route('admin.announcements') }}" class="fh-btn fh-btn-ghost px-4 py-2">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection