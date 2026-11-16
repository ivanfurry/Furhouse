@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="fh-section-label">АДМИН-ПАНЕЛЬ</span>
                <h1 class="fh-section-title">Панель управления</h1>
                <p class="text-muted mb-0">Обзор состояния площадки FurHouse</p>
            </div>
            <a href="{{ route('admin.settings') }}" class="fh-btn fh-btn-outline">
                <i class="bi bi-gear me-2"></i>Настройки
            </a>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="fh-stat-card">
                    <div class="stat-icon"><i class="bi bi-people"></i></div>
                    <div class="stat-value">{{ number_format($stats['users']) }}</div>
                    <div class="stat-label">Участники</div>
                    <div class="stat-trend">+{{ $stats['users_week'] }} за неделю</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="fh-stat-card">
                    <div class="stat-icon"><i class="bi bi-bag"></i></div>
                    <div class="stat-value">{{ number_format($stats['products']) }}</div>
                    <div class="stat-label">Товары</div>
                    <div class="stat-trend">{{ $stats['products_pending'] }} на проверке</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="fh-stat-card">
                    <div class="stat-icon"><i class="bi bi-chat-dots"></i></div>
                    <div class="stat-value">{{ $stats['tickets'] }}</div>
                    <div class="stat-label">Обращения</div>
                    <div class="stat-trend">{{ $stats['tickets_pending'] }} требуют ответа</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="fh-stat-card">
                    <div class="stat-icon"><i class="bi bi-shield-check"></i></div>
                    <div class="stat-value">{{ $stats['safety'] }}%</div>
                    <div class="stat-label">Безопасность</div>
                    <div class="stat-trend">Стабильно</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Recent Activity -->
            <div class="col-lg-8">
                <div class="fh-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 style="font-size:1.2rem; font-weight:600;">Последняя активность</h3>
                        <a href="{{ route('admin.activity') }}" class="text-decoration-none" style="color:var(--fh-primary); font-size:0.9rem; font-weight:500;">Весь журнал</a>
                    </div>

                    @forelse($recentActivity as $log)
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
                            @else
                                <i class="bi bi-activity"></i>
                            @endif
                        </div>
                        <div class="activity-text">
                            <strong>{{ $log->user?->name ?? 'Система' }}</strong> {{ str_replace('_', ' ', $log->action) }}
                            @if($log->properties)
                                <br><small class="text-muted">{{ json_encode($log->properties) }}</small>
                            @endif
                        </div>
                        <div class="activity-time">{{ $log->created_at->diffForHumans() }}</div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-4">Активность не найдена</p>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="fh-card p-4" style="background:rgba(124,92,156,0.05);">
                    <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:20px;">Быстрые действия</h3>

                    <a href="{{ route('admin.products', ['status' => 'draft']) }}" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-inbox"></i></div>
                        <div class="qa-text">Очередь модерации</div>
                        <span class="badge-count">{{ $moderationQueue }}</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <a href="{{ route('admin.tickets', ['status' => 'open']) }}" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-life-preserver"></i></div>
                        <div class="qa-text">Открытые обращения</div>
                        <span class="badge-count">{{ $openTickets }}</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <a href="{{ route('admin.events', ['status' => 'pending']) }}" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-calendar-check"></i></div>
                        <div class="qa-text">События на проверке</div>
                        <span class="badge-count">{{ $pendingEvents }}</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <a href="#" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-megaphone"></i></div>
                        <div class="qa-text">Объявление команде</div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    
                    <a href="{{ route('admin.services', ['status' => 'pending']) }}" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-briefcase"></i></div>
                        <div class="qa-text">Услуги на модерации</div>
                        <span class="badge-count">{{ $stats['services_pending'] }}</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <a href="{{ route('admin.forum', ['status' => 'pending']) }}" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-chat-square-text"></i></div>
                        <div class="qa-text">Треды на модерации</div>
                        <span class="badge-count">{{ $stats['threads_pending'] }}</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection