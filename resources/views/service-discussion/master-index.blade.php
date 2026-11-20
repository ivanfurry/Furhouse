@extends('layouts.app')

@section('title', 'Заявки на обсуждение')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fh-section-title" style="margin-bottom:8px;">Заявки на обсуждение</h1>
                <p class="text-muted mb-0">Заявки от пользователей по вашим услугам</p>
            </div>
        </div>

        @forelse($tickets as $ticket)
        <div class="fh-card p-4 mb-3" style="cursor:pointer;" onclick="window.location='{{ route('service-discussion.show', $ticket) }}'">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <h5 style="font-weight:600; margin:0;">{{ $ticket->subject }}</h5>
                        @if($ticket->status === 'open')
                            <span class="fh-badge fh-badge-warning">Новая</span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="fh-badge fh-badge-primary">В обсуждении</span>
                        @elseif($ticket->status === 'resolved')
                            <span class="fh-badge fh-badge-success">Завершено</span>
                        @endif
                    </div>
                    <p class="text-muted mb-2" style="font-size:0.9rem;">
                        {{ Str::limit($ticket->messages->last()?->message ?? $ticket->message, 100) }}
                    </p>
                    <div class="d-flex gap-3" style="font-size:0.85rem;">
                        <span class="text-muted">
                            <i class="bi bi-person me-1"></i>{{ $ticket->user->display_name }}
                        </span>
                        <span class="text-muted">
                            <i class="bi bi-clock me-1"></i>{{ $ticket->updated_at->diffForHumans() }}
                        </span>
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
            <p class="text-muted mt-3 mb-0">У вас пока нет заявок на обсуждение</p>
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