@extends('layouts.app')

@section('title', 'Мои обращения')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fh-section-title" style="margin-bottom:8px;">Мои обращения</h1>
                <p class="text-muted mb-0">История ваших обращений к персоналу</p>
            </div>
            <a href="{{ route('support.create') }}" class="fh-btn fh-btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Новое обращение
            </a>
        </div>

        @forelse($tickets as $ticket)
        <div class="fh-card p-4 mb-3" style="cursor:pointer;" onclick="window.location='{{ route('support.chat.show', $ticket) }}'">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <h5 style="font-weight:600; margin:0;">{{ $ticket->subject }}</h5>
                        @if($ticket->status === 'open')
                            <span class="fh-badge fh-badge-warning">Открыто</span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="fh-badge fh-badge-primary">В работе</span>
                        @elseif($ticket->status === 'resolved')
                            <span class="fh-badge fh-badge-success">Решено</span>
                        @endif
                    </div>
                    <p class="text-muted mb-2" style="font-size:0.9rem;">
                        {{ Str::limit($ticket->messages->last()?->message ?? $ticket->message, 100) }}
                    </p>
                    <div class="d-flex gap-3" style="font-size:0.85rem;">
                        <span class="text-muted">
                            <i class="bi bi-clock me-1"></i>{{ $ticket->updated_at->diffForHumans() }}
                        </span>
                        @if($ticket->assignedUser)
                            <span class="text-muted">
                                <i class="bi bi-person me-1"></i>{{ $ticket->assignedUser->name }}
                            </span>
                        @endif
                        <span class="text-muted">
                            <i class="bi bi-chat-dots me-1"></i>{{ $ticket->messages->count() }} сообщений
                        </span>
                    </div>
                </div>
                <div class="ms-3">
                    <i class="bi bi-chevron-right" style="font-size:1.5rem; color:var(--fh-border);"></i>
                </div>
            </div>
        </div>
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