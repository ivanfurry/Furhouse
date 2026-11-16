@extends('layouts.app')

@section('title', 'Обращения')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="fh-section-label">АДМИН-ПАНЕЛЬ</span>
                <h1 class="fh-section-title">Обращения</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="fh-btn fh-btn-outline">
                <i class="bi bi-arrow-left me-2"></i>Назад
            </a>
        </div>

        <form method="GET" action="{{ route('admin.tickets') }}" class="mb-4">
            <select name="status" class="form-select fh-form-control" style="max-width:300px;" onchange="this.form.submit()">
                <option value="">Все статусы</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Открытые</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>В работе</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Решённые</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Закрытые</option>
            </select>
        </form>

        <div class="fh-table">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Тема</th>
                        <th>Пользователь</th>
                        <th>Приоритет</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $ticket->subject }}</div>
                            <small class="text-muted">{{ Str::limit($ticket->message, 60) }}</small>
                        </td>
                        <td>{{ $ticket->user->name }}</td>
                        <td>
                            @if($ticket->priority === 'high')
                                <span class="fh-badge" style="background:rgba(196,92,92,0.12); color:var(--fh-danger);">Высокий</span>
                            @elseif($ticket->priority === 'medium')
                                <span class="fh-badge fh-badge-warning">Средний</span>
                            @else
                                <span class="fh-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">Низкий</span>
                            @endif
                        </td>
                        <td>
                            @if($ticket->status === 'open')
                                <span class="fh-status-pending">Открыто</span>
                            @elseif($ticket->status === 'in_progress')
                                <span class="fh-badge fh-badge-primary">В работе</span>
                            @elseif($ticket->status === 'resolved')
                                <span class="fh-status-online">Решено</span>
                            @else
                                <span class="fh-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">Закрыто</span>
                            @endif
                        </td>
                        <td>{{ $ticket->created_at->format('d.m.Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($ticket->status === 'open')
                                    <form method="POST" action="{{ route('admin.tickets.assign', $ticket) }}">
                                        @csrf
                                        <button type="submit" class="fh-btn fh-btn-primary" style="padding:6px 12px; font-size:0.85rem;">
                                            Взять
                                        </button>
                                    </form>
                                @endif
                                @if($ticket->status !== 'resolved' && $ticket->status !== 'closed')
                                    <form method="POST" action="{{ route('admin.tickets.resolve', $ticket) }}">
                                        @csrf
                                        <button type="submit" class="fh-btn fh-btn-ghost" style="padding:6px 12px; font-size:0.85rem; color:var(--fh-success);">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Обращения не найдены</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</section>
@endsection