@extends('layouts.app')

@section('title', 'Встречи')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="fh-section-label">АДМИН-ПАНЕЛЬ</span>
                <h1 class="fh-section-title">Встречи</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="fh-btn fh-btn-outline">
                <i class="bi bi-arrow-left me-2"></i>Назад
            </a>
        </div>

        <form method="GET" action="{{ route('admin.events') }}" class="mb-4">
            <select name="status" class="form-select fh-form-control" style="max-width:300px;" onchange="this.form.submit()">
                <option value="">Все статусы</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>На проверке</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Одобренные</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменённые</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершённые</option>
            </select>
        </form>

        <div class="fh-table">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Встреча</th>
                        <th>Организатор</th>
                        <th>Город</th>
                        <th>Дата</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $event->title }}</div>
                            <small class="text-muted">{{ $event->type_label }}</small>
                        </td>
                        <td>{{ $event->user->name }}</td>
                        <td>{{ $event->city }}</td>
                        <td>{{ $event->starts_at->format('d.m.Y H:i') }}</td>
                        <td>
                            @if($event->status === 'approved')
                                <span class="fh-status-online">Одобрена</span>
                            @elseif($event->status === 'pending')
                                <span class="fh-status-pending">На проверке</span>
                            @else
                                <span class="fh-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">{{ $event->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($event->status === 'pending')
                                    <form method="POST" action="{{ route('admin.events.moderate', $event) }}">
                                        @csrf
                                        <button type="submit" class="fh-btn fh-btn-primary" style="padding:6px 12px; font-size:0.85rem;">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('events.show', $event) }}" class="fh-btn fh-btn-ghost" style="padding:6px 12px; font-size:0.85rem;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Встречи не найдены</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $events->links() }}
        </div>
        @endif
    </div>
</section>
@endsection