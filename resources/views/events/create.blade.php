@extends('layouts.app')

@section('title', 'Создать встречу')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fh-card p-4 p-md-5">
                    <h2 style="font-size:1.6rem; font-weight:500; margin-bottom:8px;">Создать встречу</h2>
                    <p class="text-muted mb-4">Расскажите о вашем событии</p>

                    @if($errors->any())
                    <div class="alert alert-danger fh-alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Название</label>
                            <input type="text" name="title" class="form-control fh-form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Тип события</label>
                                <select name="type" class="form-select fh-form-control" required>
                                    <option value="">Выберите тип</option>
                                    <option value="walk" {{ old('type') == 'walk' ? 'selected' : '' }}>Прогулка</option>
                                    <option value="picnic" {{ old('type') == 'picnic' ? 'selected' : '' }}>Пикник</option>
                                    <option value="creative" {{ old('type') == 'creative' ? 'selected' : '' }}>Творчество</option>
                                    <option value="games" {{ old('type') == 'games' ? 'selected' : '' }}>Игры</option>
                                    <option value="festival" {{ old('type') == 'festival' ? 'selected' : '' }}>Фестиваль</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Другое</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Город</label>
                                <input type="text" name="city" class="form-control fh-form-control" value="{{ old('city') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Место встречи</label>
                            <input type="text" name="location" class="form-control fh-form-control" value="{{ old('location') }}" placeholder="Например: Парк Горького, у фонтана">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Дата и время начала</label>
                                <input type="datetime-local" name="starts_at" class="form-control fh-form-control" value="{{ old('starts_at') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Дата и время окончания</label>
                                <input type="datetime-local" name="ends_at" class="form-control fh-form-control" value="{{ old('ends_at') }}">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Максимум гостей</label>
                                <input type="number" name="max_guests" class="form-control fh-form-control" value="{{ old('max_guests') }}" min="2">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Цена (₽, 0 = бесплатно)</label>
                                <input type="number" name="price" class="form-control fh-form-control" value="{{ old('price', 0) }}" min="0" step="0.01">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Описание</label>
                            <textarea name="description" class="form-control fh-form-control" rows="5" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Краткое описание</label>
                            <textarea name="short_description" class="form-control fh-form-control" rows="2" maxlength="500">{{ old('short_description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Изображение</label>
                            <input type="file" name="image" class="form-control fh-form-control" accept="image/*">
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="fh-btn fh-btn-primary px-4 py-2">
                                <i class="bi bi-check me-2"></i>Создать встречу
                            </button>
                            <a href="{{ route('events.index') }}" class="fh-btn fh-btn-ghost px-4 py-2">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection