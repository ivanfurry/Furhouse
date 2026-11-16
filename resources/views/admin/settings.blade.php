@extends('layouts.app')

@section('title', 'Настройки')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fh-card p-4 p-md-5">
                    <h2 style="font-size:1.6rem; font-weight:500; margin-bottom:24px;">Настройки площадки</h2>

                    @if(session('success'))
                    <div class="alert alert-success fh-alert">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PUT')

                        <h5 style="font-weight:600; margin-bottom:16px;">Общие</h5>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Название площадки</label>
                            <input type="text" name="site_name" class="form-control fh-form-control" value="{{ config('app.name') }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Описание</label>
                            <textarea name="site_description" class="form-control fh-form-control" rows="3">Место, где идеи, мастерство и добрые встречи находят свой дом.</textarea>
                        </div>

                        <h5 style="font-weight:600; margin-bottom:16px; margin-top:24px;">Модерация</h5>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="auto_approve_products" id="autoApprove">
                            <label class="form-check-label" for="autoApprove">Автоодобрение товаров от проверенных мастеров</label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="require_email_verification" id="requireEmail" checked>
                            <label class="form-check-label" for="requireEmail">Требовать подтверждение email</label>
                        </div>

                        <h5 style="font-weight:600; margin-bottom:16px; margin-top:24px;">Уведомления</h5>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="notify_new_products" id="notifyProducts" checked>
                            <label class="form-check-label" for="notifyProducts">Уведомлять о новых товарах</label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="notify_new_events" id="notifyEvents" checked>
                            <label class="form-check-label" for="notifyEvents">Уведомлять о новых встречах</label>
                        </div>

                        <button type="submit" class="fh-btn fh-btn-primary px-4 py-2">
                            <i class="bi bi-check me-2"></i>Сохранить настройки
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection