@extends('layouts.app')

@section('title', 'Услуги мастеров')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="fh-section-label">АДМИН-ПАНЕЛЬ</span>
                <h1 class="fh-section-title">Услуги мастеров</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="fh-btn fh-btn-outline">
                <i class="bi bi-arrow-left me-2"></i>Назад
            </a>
        </div>

        <form method="GET" action="{{ route('admin.services') }}" class="mb-4">
            <select name="status" class="form-select fh-form-control" style="max-width:300px;" onchange="this.form.submit()">
                <option value="">Все статусы</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>На модерации</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Одобренные</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклонённые</option>
            </select>
        </form>

        <div class="fh-table">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Услуга</th>
                        <th>Мастер</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $service->title }}</div>
                            <small class="text-muted">{{ $service->deadline }}</small>
                        </td>
                        <td>{{ $service->user->name }}</td>
                        <td>{{ $service->category_label }}</td>
                        <td><strong>{{ $service->formatted_price }}</strong></td>
                        <td>
                            @if($service->status === 'approved')
                                <span class="fh-status-online">Одобрена</span>
                            @elseif($service->status === 'pending')
                                <span class="fh-status-pending">На модерации</span>
                            @else
                                <span class="fh-badge" style="background:rgba(196,92,92,0.12); color:var(--fh-danger);">Отклонена</span>
                            @endif
                        </td>
                        <td>
                            @if($service->status === 'pending')
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('admin.services.moderate', $service) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="fh-btn fh-btn-primary" style="padding:6px 12px; font-size:0.85rem;">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.services.moderate', $service) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="fh-btn fh-btn-ghost" style="padding:6px 12px; font-size:0.85rem; color:var(--fh-danger);">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Услуги не найдены</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($services->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $services->links() }}
        </div>
        @endif
    </div>
</section>
@endsection