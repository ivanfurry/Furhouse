@extends('layouts.app')

@section('title', 'Предложить услугу')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fh-card p-4 p-md-5">
                    <h2 style="font-size:1.6rem; font-weight:500; margin-bottom:8px;">Предложить услугу</h2>
                    <p class="text-muted mb-4">Расскажите о вашей услуге и установите ставку</p>

                    @if($errors->any())
                    <div class="alert alert-danger fh-alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('masters.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Название услуги</label>
                            <input type="text" name="title" class="form-control fh-form-control" value="{{ old('title') }}" placeholder="Например: Иллюстрация персонажа" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Категория</label>
                            <select name="category" class="form-select fh-form-control" required>
                                <option value="">Выберите категорию</option>
                                <option value="art" {{ old('category') == 'art' ? 'selected' : '' }}>Иллюстрация</option>
                                <option value="costume" {{ old('category') == 'costume' ? 'selected' : '' }}>Костюмы</option>
                                <option value="3d" {{ old('category') == '3d' ? 'selected' : '' }}>3D и VR</option>
                                <option value="music" {{ old('category') == 'music' ? 'selected' : '' }}>Музыка</option>
                                <option value="design" {{ old('category') == 'design' ? 'selected' : '' }}>Дизайн</option>
                                <option value="craft" {{ old('category') == 'craft' ? 'selected' : '' }}>Крафт</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Описание</label>
                            <textarea name="description" class="form-control fh-form-control" rows="5" placeholder="Расскажите подробно о вашей услуге..." required>{{ old('description') }}</textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ставка от (₽)</label>
                                <input type="number" name="price_from" class="form-control fh-form-control" value="{{ old('price_from') }}" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Срок выполнения</label>
                                <input type="text" name="deadline" class="form-control fh-form-control" value="{{ old('deadline') }}" placeholder="Например: 7-10 дней" required>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="fh-btn fh-btn-primary px-4 py-2">
                                <i class="bi bi-check me-2"></i>Опубликовать
                            </button>
                            <a href="{{ route('masters.index') }}" class="fh-btn fh-btn-ghost px-4 py-2">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection