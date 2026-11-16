@extends('layouts.app')

@section('title', 'Треды форума')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="fh-section-label">АДМИН-ПАНЕЛЬ</span>
                <h1 class="fh-section-title">Треды форума</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="fh-btn fh-btn-outline">
                <i class="bi bi-arrow-left me-2"></i>Назад
            </a>
        </div>

        <form method="GET" action="{{ route('admin.forum') }}" class="mb-4">
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
                        <th>Тред</th>
                        <th>Автор</th>
                        <th>Доска</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($threads as $thread)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $thread->title }}</div>
                            <small class="text-muted">{{ Str::limit($thread->body, 50) }}</small>
                        </td>
                        <td>{{ $thread->user->name }}</td>
                        <td>{{ $thread->board->name }}</td>
                        <td>
                            @if($thread->status === 'approved')
                                <span class="fh-status-online">Одобрен</span>
                            @elseif($thread->status === 'pending')
                                <span class="fh-status-pending">На модерации</span>
                            @else
                                <span class="fh-badge" style="background:rgba(196,92,92,0.12); color:var(--fh-danger);">Отклонён</span>
                            @endif
                        </td>
                        <td>{{ $thread->created_at->format('d.m.Y') }}</td>
                        <td>
                            @if($thread->status === 'pending')
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('admin.forum.moderate', $thread) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="fh-btn fh-btn-primary" style="padding:6px 12px; font-size:0.85rem;">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.forum.moderate', $thread) }}">
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
                        <td colspan="6" class="text-center py-4 text-muted">Треды не найдены</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($threads->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $threads->links() }}
        </div>
        @endif
    </div>
</section>
@endsection