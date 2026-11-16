@extends('layouts.app')

@section('title', 'Разместить товар')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fh-card p-4 p-md-5">
                    <h2 style="font-size:1.6rem; font-weight:500; margin-bottom:8px;">Разместить товар</h2>
                    <p class="text-muted mb-4">Заполните информацию о вашем товаре</p>

                    @if($errors->any())
                    <div class="alert alert-danger fh-alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Название</label>
                            <input type="text" name="title" class="form-control fh-form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Категория</label>
                            <select name="category_id" class="form-select fh-form-control" required>
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Описание</label>
                            <textarea name="description" class="form-control fh-form-control" rows="5" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Цена (₽)</label>
                                <input type="number" name="price" class="form-control fh-form-control" value="{{ old('price') }}" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Тип доставки</label>
                                <select name="delivery_type" class="form-select fh-form-control">
                                    <option value="">Не указано</option>
                                    <option value="russia" {{ old('delivery_type') == 'russia' ? 'selected' : '' }}>По России</option>
                                    <option value="pickup" {{ old('delivery_type') == 'pickup' ? 'selected' : '' }}>Самовывоз</option>
                                    <option value="digital" {{ old('delivery_type') == 'digital' ? 'selected' : '' }}>Цифровой товар</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Изображение</label>
                            <input type="file" name="image" class="form-control fh-form-control" accept="image/*">
                            <small class="text-muted">Максимум 2 МБ</small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStock" {{ old('in_stock', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="inStock">Товар в наличии</label>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="fh-btn fh-btn-primary px-4 py-2">
                                <i class="bi bi-check me-2"></i>Разместить
                            </button>
                            <a href="{{ route('products.index') }}" class="fh-btn fh-btn-ghost px-4 py-2">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection