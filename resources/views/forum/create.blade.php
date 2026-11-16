@extends('layouts.app')

@section('title', 'Создать тред')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fh-card p-4 p-md-5">
                    <h2 style="font-size:1.6rem; font-weight:500; margin-bottom:8px;">Новый тред</h2>
                    <p class="text-muted mb-4">Выберите доску и расскажите о теме</p>

                    @if($errors->any())
                    <div class="alert alert-danger fh-alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('forum.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Доска</label>
                            <select name="board_id" class="form-select fh-form-control" required>
                                <option value="">Выберите доску</option>
                                @foreach($boards as $board)
                                    <option value="{{ $board->id }}" {{ (old('board_id') ?? $selectedBoard) == $board->id ? 'selected' : '' }}>
                                        /{{ $board->prefix }}/ — {{ $board->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Заголовок</label>
                            <input type="text" name="title" class="form-control fh-form-control" value="{{ old('title') }}" required maxlength="255">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Сообщение</label>
                            <textarea name="body" class="form-control fh-form-control" rows="8" required>{{ old('body') }}</textarea>
                            <small class="text-muted">Минимум 10 символов</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Изображение (опционально)</label>
                            <input type="file" name="image" class="form-control fh-form-control" accept="image/*">
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="fh-btn fh-btn-primary px-4 py-2">
                                <i class="bi bi-check me-2"></i>Создать тред
                            </button>
                            <a href="{{ route('forum.index') }}" class="fh-btn fh-btn-ghost px-4 py-2">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection