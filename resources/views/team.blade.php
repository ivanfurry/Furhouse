@extends('layouts.app')

@section('title', 'Команда FurHouse')

@section('content')
<!-- Page Header -->
<section class="fh-page-header">
    <div class="container">
        <span class="fh-section-label">ПАНЕЛЬ УПРАВЛЕНИЯ</span>
        <h1 class="fh-section-title">Команда FurHouse</h1>
        <p class="fh-section-subtitle">Роли, доступные действия и состояние площадки — в одном спокойном пространстве.</p>
    </div>
</section>

<!-- Stats -->
<section class="pb-4">
    <div class="container">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="fh-stat-card">
                    <div class="stat-icon"><i class="bi bi-people"></i></div>
                    <div class="stat-value">12 842</div>
                    <div class="stat-label">Участники</div>
                    <div class="stat-trend">+124 за неделю</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="fh-stat-card">
                    <div class="stat-icon"><i class="bi bi-bag"></i></div>
                    <div class="stat-value">1 846</div>
                    <div class="stat-label">Товары</div>
                    <div class="stat-trend">32 на проверке</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="fh-stat-card">
                    <div class="stat-icon"><i class="bi bi-chat-dots"></i></div>
                    <div class="stat-value">{{ \App\Models\SupportTicket::count() }}</div>
                    <div class="stat-label">Обращения</div>
                    <div class="stat-trend">{{ \App\Models\SupportTicket::where('status', 'open')->count() }} требуют ответа</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="fh-stat-card">
                    <div class="stat-icon"><i class="bi bi-shield-check"></i></div>
                    <div class="stat-value">99,8%</div>
                    <div class="stat-label">Безопасность</div>
                    <div class="stat-trend">Стабильно</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Members -->
<section class="pb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fh-section-title" style="font-size:1.5rem;">Роли и доступ</h2>
            @auth
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="fh-btn fh-btn-outline">
                    <i class="bi bi-person-plus me-2"></i>Добавить участника
                </a>
                @endif
            @endauth
        </div>

        <div class="row g-4">
            @forelse($teamMembers as $member)
            <div class="col-md-6 col-lg-4">
                <div class="fh-team-card">
                    <div class="member-header">
                        <div>
                            <span class="fh-badge fh-badge-primary mb-2">
                                {{ $member->role === 'admin' ? 'Администратор' : 'Помощник администратора' }}
                            </span>
                        </div>
                        <span class="fh-status-online">В сети</span>
                    </div>

                    <div class="member-info">
                        <img src="{{ $member->avatar_url }}" class="fh-avatar fh-avatar-lg" alt="">
                        <div>
                            <div style="font-weight:600;">{{ $member->display_name }}</div>
                            <div style="font-size:0.85rem; color:var(--fh-text-muted);">
                                {{ $member->role === 'admin' ? 'Администратор' : 'Помощник администратора' }}
                            </div>
                        </div>
                    </div>

                    <div class="member-tags">
                        @if($member->isAdmin())
                            <span class="fh-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">Полный доступ</span>
                            <span class="fh-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">Споры и выплаты</span>
                        @else
                            <span class="fh-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">Модерация</span>
                            <span class="fh-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">Обращения</span>
                        @endif
                    </div>

                    <div class="member-actions">
                        <a href="{{ route('users.show', $member) }}" class="fh-btn fh-btn-primary">Профиль</a>
                        @auth
                            <a href="{{ route('support.create', ['to' => $member->id]) }}" class="fh-btn fh-btn-ghost">Сообщение</a>
                        @else
                            <a href="{{ route('login') }}" class="fh-btn fh-btn-ghost">Сообщение</a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Команда пока пуста</p>
            </div>
            @endforelse
        </div>

        <!-- Activity & Quick Actions -->
        <div class="row g-4 mt-4">
            <div class="col-lg-8">
                <div class="fh-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 style="font-size:1.2rem; font-weight:600;">Последняя активность</h3>
                        <a href="#" class="text-decoration-none" style="color:var(--fh-primary); font-size:0.9rem; font-weight:500;">Весь журнал</a>
                    </div>

                    <div class="fh-activity-item">
                        <div class="activity-icon"><i class="bi bi-shield-check"></i></div>
                        <div class="activity-text">VioletFox закрыла спор по заказу #1842</div>
                        <div class="activity-time">8 минут назад</div>
                    </div>
                    <div class="fh-activity-item">
                        <div class="activity-icon"><i class="bi bi-chat-dots"></i></div>
                        <div class="activity-text">NorthPaw ответил на обращение #682</div>
                        <div class="activity-time">24 минуты назад</div>
                    </div>
                    <div class="fh-activity-item">
                        <div class="activity-icon"><i class="bi bi-code-slash"></i></div>
                        <div class="activity-text">PixelLynx опубликовала версию 2.4.1</div>
                        <div class="activity-time">1 час назад</div>
                    </div>
                    <div class="fh-activity-item">
                        <div class="activity-icon"><i class="bi bi-person-check"></i></div>
                        <div class="activity-text">Одобрен новый организатор встреч</div>
                        <div class="activity-time">2 часа назад</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="fh-card p-4" style="background:rgba(124,92,156,0.05);">
                    <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:20px;">Быстрые действия</h3>

                    <a href="{{ route('admin.products') }}" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-inbox"></i></div>
                        <div class="qa-text">Очередь модерации</div>
                        <span class="badge-count">32</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <a href="{{ route('admin.tickets') }}" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-life-preserver"></i></div>
                        <div class="qa-text">Открытые обращения</div>
                        <span class="badge-count">14</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <a href="{{ route('admin.events') }}" class="fh-quick-action">
                        <div class="qa-icon"><i class="bi bi-calendar-check"></i></div>
                        <div class="qa-text">События на проверке</div>
                        <span class="badge-count">6</span>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <a href="{{ route('admin.announcements') }}" class="fh-quick-action">
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