@extends('layouts.app')

@section('title', 'Создать обращение')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="support-form-card">
            <h2 class="support-form-title">Создать обращение</h2>
            <p class="support-form-subtitle">Опишите вашу проблему или вопрос — персонал ответит в ближайшее время</p>

            @if($errors->any())
            <div class="alert alert-danger fh-alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('support.store') }}">
                @csrf

                @if($assignedTo)
                <div class="support-form-alert">
                    <i class="bi bi-info-circle me-2"></i>
                    Обращение будет направлено: <strong>{{ $assignedTo->display_name }}</strong>
                    <input type="hidden" name="assigned_to" value="{{ $assignedTo->id }}">
                </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-semibold">Тема обращения</label>
                    <input type="text" name="subject" class="form-control fh-form-control" value="{{ old('subject') }}" placeholder="Кратко опишите суть вопроса" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Приоритет</label>
                    <select name="priority" class="form-select fh-form-control" required>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Низкий — общий вопрос</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Средний — нужна помощь</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Высокий — срочная проблема</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Сообщение</label>
                    <textarea name="message" class="form-control fh-form-control" rows="6" placeholder="Подробно опишите вашу проблему или вопрос..." required>{{ old('message') }}</textarea>
                    <small class="text-muted">Минимум 10 символов</small>
                </div>

                <div class="support-form-actions">
                    <button type="submit" class="fh-btn fh-btn-primary px-4 py-2">
                        <i class="bi bi-send me-2"></i>Отправить обращение
                    </button>
                    <a href="{{ route('support.index') }}" class="fh-btn fh-btn-ghost px-4 py-2">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection