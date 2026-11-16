@extends('layouts.app')

@section('title', 'Мои обращения')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fh-section-title">Мои обращения</h1>
                <p class="text-muted">История ваших обращений к персоналу</p>
            </div>
            <a href="{{ route('support.create') }}" class="fh-btn fh-btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Новое обращение
            </a>
        </div>

        @forelse($tickets as $ticket)
        <div class="tickets-list-item">
            <div class="tickets-list-header">
                <h5 class="tickets-list-title">
                    <a href="{{ route('support.show', $ticket) }}">{{ $ticket->subject }}</a>
                </h5>
                @if($ticket->status === 'open')
                    <span class="support-status-badge support-status-open">Открыто</span>
                @elseif($ticket->status === 'in_progress')
                    <span class="support-status-badge support-status-progress">В работе</span>
                @elseif($ticket->status === 'resolved')
                    <span class="support-status-badge support-status-resolved">Решено</span>
                @else
                    <span class="support-status-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">Закрыто</span>
                @endif
            </div>
            <p class="tickets-list-excerpt">{{ Str::limit($ticket->message, 150) }}</p>
            <div class="tickets-list-meta">
                <span><i class="bi bi-clock me-1"></i>{{ $ticket->created_at->format('d.m.Y H:i') }}</span>
                @if($ticket->assignedUser)
                    <span><i class="bi bi-person me-1"></i>{{ $ticket->assignedUser->name }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="support-form-card text-center">
            <i class="bi bi-inbox" style="font-size:3rem; color:var(--fh-border);"></i>
            <p class="text-muted mt-3 mb-0">У вас пока нет обращений</p>
            <a href="{{ route('support.create') }}" class="fh-btn fh-btn-primary mt-3">
                Создать первое обращение
            </a>
        </div>
        @endforelse

        @if($tickets->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</section>
@endsection