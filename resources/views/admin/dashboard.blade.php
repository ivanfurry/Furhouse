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
                            @php
                                $iconMap = [
                                    'user_verified' => 'bi-patch-check-fill',
                                    'user_unverified' => 'bi-patch-exclamation-fill',
                                    'user_restricted' => 'bi-shield-x',
                                    'user_restrictions_removed' => 'bi-shield-check',
                                    'user_updated' => 'bi-person-gear',
                                    'user_deactivated' => 'bi-person-x',
                                    'user_created' => 'bi-person-plus',
                                    'product_created' => 'bi-bag-plus',
                                    'product_approved' => 'bi-check-circle',
                                    'product_rejected' => 'bi-x-circle',
                                    'event_approved' => 'bi-calendar-check',
                                    'service_approved' => 'bi-briefcase-check',
                                    'service_rejected' => 'bi-briefcase-x',
                                    'thread_approved' => 'bi-chat-square-check',
                                    'thread_rejected' => 'bi-chat-square-x',
                                    'thread_created' => 'bi-chat-square-plus',
                                    'ticket_assigned' => 'bi-life-preserver',
                                    'ticket_resolved' => 'bi-check2-circle',
                                    'settings_updated' => 'bi-gear-fill',
                                ];
                                $icon = $iconMap[$log->action] ?? 'bi-activity';

                                $labelMap = [
                                    'user_verified' => 'Пользователь верифицирован',
                                    'user_unverified' => 'Верификация отменена',
                                    'user_restricted' => 'Наложены ограничения',
                                    'user_restrictions_removed' => 'Ограничения сняты',
                                    'user_updated' => 'Пользователь обновлён',
                                    'user_deactivated' => 'Пользователь деактивирован',
                                    'user_created' => 'Пользователь создан',
                                    'product_created' => 'Товар создан',
                                    'product_approved' => 'Товар одобрен',
                                    'product_rejected' => 'Товар отклонён',
                                    'event_approved' => 'Встреча одобрена',
                                    'service_approved' => 'Услуга одобрена',
                                    'service_rejected' => 'Услуга отклонена',
                                    'thread_approved' => 'Тред одобрен',
                                    'thread_rejected' => 'Тред отклонён',
                                    'thread_created' => 'Тред создан',
                                    'ticket_assigned' => 'Обращение назначено',
                                    'ticket_resolved' => 'Обращение решено',
                                    'settings_updated' => 'Настройки обновлены',
                                ];
                                $label = $labelMap[$log->action] ?? str_replace('_', ' ', $log->action);
                            @endphp
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <div class="activity-text">
                            <strong>{{ $log->user?->name ?? 'Система' }}</strong> — {{ $label }}

                            @if($log->properties && count($log->properties) > 0)
                                @php
                                    // Фильтруем чувствительные поля
                                    $sensitiveKeys = ['_token', '_method', 'password', 'password_confirmation', 'current_password'];
                                    $cleanProps = collect($log->properties)->except($sensitiveKeys)->all();

                                    // Маппинг полей на русский
                                    $fieldLabels = [
                                        'can_post_products' => 'Размещение товаров',
                                        'can_post_services' => 'Размещение услуг',
                                        'can_create_events' => 'Создание встреч',
                                        'can_post_forum' => 'Публикация в форуме',
                                        'can_comment' => 'Комментирование',
                                        'restriction_reason' => 'Причина',
                                        'restriction_until' => 'Срок',
                                        'restricted_by' => 'Кто ограничил',
                                        'is_verified' => 'Верификация',
                                        'is_active' => 'Активен',
                                        'role' => 'Роль',
                                        'name' => 'Имя',
                                        'email' => 'Email',
                                        'nickname' => 'Никнейм',
                                    ];

                                    // Маппинг булевых значений
                                    $boolLabels = [
                                        true => '✓',
                                        false => '✗',
                                        1 => '✓',
                                        0 => '✗',
                                    ];
                                @endphp

                                <div class="mt-2 p-2" style="background:var(--fh-bg); border-radius:8px; font-size:0.8rem;">
                                    @foreach($cleanProps as $key => $value)
                                        @if(!is_null($value) && $value !== '')
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">{{ $fieldLabels[$key] ?? $key }}:</span>
                                                <span style="font-weight:500;">
                                                    @if(is_bool($value) || in_array($value, [0, 1, '0', '1']))
                                                        {{ $boolLabels[$value] ?? $value }}
                                                    @elseif($key === 'restriction_until' && $value)
                                                        {{ \Carbon\Carbon::parse($value)->format('d.m.Y H:i') }}
                                                    @elseif($key === 'restricted_by')
                                                        {{ \App\Models\User::find($value)?->name ?? '—' }}
                                                    @else
                                                        {{ is_string($value) && strlen($value) > 60 ? Str::limit($value, 60) : $value }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
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

                    <!-- НОВАЯ КНОПКА: Управление пользователями -->
                    <a href="{{ route('admin.users') }}" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="qa-text">Управление пользователями</div>
                        @if($stats['users_unverified'] > 0)
                            <span class="badge-count">{{ $stats['users_unverified'] }}</span>
                        @endif
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <!-- Кнопка для не верифицированных пользователей -->
                    @if($stats['users_unverified'] > 0)
                    <a href="{{ route('admin.users', ['verified' => '0']) }}" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-patch-check"></i></div>
                        <div class="qa-text">Требуют верификации</div>
                        <span class="badge-count">{{ $stats['users_unverified'] }}</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    @endif

                    <!-- Кнопка для пользователей с ограничениями -->
                    @if($stats['users_restricted'] > 0)
                    <a href="{{ route('admin.users', ['restricted' => '1']) }}" class="fh-quick-action">
                        <div class="qa-icon" style="background:rgba(196,92,92,0.1); color:var(--fh-danger);"><i class="bi bi-shield-x"></i></div>
                        <div class="qa-text">С ограничениями</div>
                        <span class="badge-count" style="background:var(--fh-danger);">{{ $stats['users_restricted'] }}</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    @endif

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

                    <a href="{{ route('admin.announcements.create') }}" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-megaphone"></i></div>
                        <div class="qa-text">Объявление команде</div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection