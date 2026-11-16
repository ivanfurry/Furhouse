@extends('layouts.app')

@section('title', 'Журнал активности')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="fh-section-label">АДМИН-ПАНЕЛЬ</span>
                <h1 class="fh-section-title">Журнал активности</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="fh-btn fh-btn-outline">
                <i class="bi bi-arrow-left me-2"></i>Назад
            </a>
        </div>

        <div class="fh-card p-4">
            @forelse($logs as $log)
            <div class="fh-activity-item">
                <div class="activity-icon">
                    @if(str_contains($log->action, 'user'))
                        <i class="bi bi-person"></i>
                    @elseif(str_contains($log->action, 'product'))
                        <i class="bi bi-bag"></i>
                    @elseif(str_contains($log->action, 'event'))
                        <i class="bi bi-calendar"></i>
                    @elseif(str_contains($log->action, 'ticket'))
                        <i class="bi bi-life-preserver"></i>
                    @elseif(str_contains($log->action, 'settings'))
                        <i class="bi bi-gear"></i>
                    @else
                        <i class="bi bi-activity"></i>
                    @endif
                </div>
                <div class="activity-text">
                    <strong>{{ $log->user?->name ?? 'Система' }}</strong> {{ str_replace('_', ' ', $log->action) }}
                    @if($log->model_type)
                        <br><small class="text-muted">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</small>
                    @endif
                </div>
                <div class="activity-time">
                    {{ $log->created_at->format('d.m.Y H:i') }}
                    <br><small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                </div>
            </div>
            @empty
            <p class="text-muted text-center py-4">Записей не найдено</p>
            @endforelse
        </div>

        @if($logs->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</section>
@endsection