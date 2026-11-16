@extends('layouts.app')

@section('title', 'Объявления команде')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="announcements-header">
            <div>
                <span class="fh-section-label">АДМИН-ПАНЕЛЬ</span>
                <h1 class="announcements-header-title">Объявления команде</h1>
                <p class="announcements-header-subtitle">Публикуйте важные сообщения для администраторов и модераторов</p>
            </div>
            <div class="announcements-actions">
                <a href="{{ route('admin.announcements.create') }}" class="fh-btn fh-btn-primary">
                    <i class="bi bi-plus me-2"></i>Новое объявление
                </a>
                <a href="{{ route('admin.dashboard') }}" class="fh-btn fh-btn-outline">
                    <i class="bi bi-arrow-left me-2"></i>Назад
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.announcements') }}" class="announcements-filter">
            <select name="priority" class="form-select fh-form-control" onchange="this.form.submit()">
                <option value="">Все приоритеты</option>
                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Срочные</option>
                <option value="important" {{ request('priority') == 'important' ? 'selected' : '' }}>Важные</option>
                <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Обычные</option>
            </select>
        </form>

        @forelse($announcements as $announcement)
        <div class="announcement-card announcement-card--{{ $announcement->priority }} {{ $announcement->is_pinned ? 'announcement-card--pinned' : '' }}">
            <div class="announcement-card-badges">
                @if($announcement->is_pinned)
                    <span class="announcement-badge announcement-badge--pinned">
                        <i class="bi bi-pin-fill"></i>Закреплено
                    </span>
                @endif
                <span class="announcement-badge announcement-badge--{{ $announcement->priority }}">
                    {{ $announcement->priority_label }}
                </span>
            </div>

            <h5 class="announcement-card-title">{{ $announcement->title }}</h5>
            <p class="announcement-card-body announcement-card-body--truncated">{{ $announcement->body }}</p>

            <div class="announcement-card-meta">
                <div class="announcement-card-meta-item">
                    <i class="bi bi-person"></i>
                    <span>{{ $announcement->user->name }}</span>
                </div>
                <div class="announcement-card-meta-item">
                    <i class="bi bi-clock"></i>
                    <span>{{ $announcement->created_at->format('d.m.Y H:i') }}</span>
                </div>
                @if($announcement->expires_at)
                    <div class="announcement-card-meta-item">
                        <i class="bi bi-hourglass"></i>
                        <span>до {{ $announcement->expires_at->format('d.m.Y') }}</span>
                    </div>
                @endif
                <div class="announcement-card-actions">
                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" 
                          onsubmit="return confirm('Удалить объявление?')" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="fh-btn fh-btn-ghost" style="padding:6px 12px; font-size:0.85rem; color:var(--fh-danger);">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="announcements-empty">
            <div class="announcements-empty-icon"><i class="bi bi-megaphone"></i></div>
            <p class="announcements-empty-text">Объявлений пока нет</p>
            <a href="{{ route('admin.announcements.create') }}" class="fh-btn fh-btn-primary">
                Создать первое объявление
            </a>
        </div>
        @endforelse

        @if($announcements->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
</section>
@endsection