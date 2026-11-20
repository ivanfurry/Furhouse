@extends('layouts.app')

@section('title', 'Мои обращения')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 class="fh-section-title" style="margin-bottom:8px;">Мои обращения</h1>
                <p class="text-muted mb-0">История ваших обращений к персоналу</p>
            </div>
            <a href="{{ route('support.create') }}" class="fh-btn fh-btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Новое обращение
            </a>
        </div>

        @forelse($tickets as $ticket)
        <a href="{{ route('support.chat.show', $ticket) }}" class="ticket-card" style="text-decoration:none; color:inherit; display:block;">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <h5 style="font-weight:600; margin:0;">{{ $ticket->subject }}</h5>
                        <span class="fh-badge" style="background:{{ $ticket->status_color }}20; color:{{ $ticket->status_color }};">
                            {{ $ticket->status_label }}
                        </span>
                    </div>
                    <p class="text-muted mb-2" style="font-size:0.9rem;">
                        {{ Str::limit($ticket->messages->last()?->message ?? $ticket->message, 150) }}
                    </p>
                    <div class="d-flex gap-3" style="font-size:0.85rem; color:var(--fh-text-muted);">
                        <span><i class="bi bi-clock me-1"></i>{{ $ticket->created_at->format('d.m.Y H:i') }}</span>
                        @if($ticket->assignedUser)
                            <span><i class="bi bi-person me-1"></i>{{ $ticket->assignedUser->name }}</span>
                        @endif
                        <span><i class="bi bi-chat-dots me-1"></i>{{ $ticket->messages->count() }} сообщ.</span>
                    </div>
                </div>
                <div class="ms-3 d-flex align-items-center">
                    <i class="bi bi-chevron-right" style="font-size:1.5rem; color:var(--fh-border);"></i>
                </div>
            </div>
        </a>
        @empty
        <div class="fh-card p-5 text-center">
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