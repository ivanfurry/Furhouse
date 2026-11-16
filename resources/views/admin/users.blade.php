@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="fh-section-label">АДМИН-ПАНЕЛЬ</span>
                <h1 class="fh-section-title">Пользователи</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="fh-btn fh-btn-outline">
                <i class="bi bi-arrow-left me-2"></i>Назад
            </a>
        </div>

        <!-- Поиск -->
        <form method="GET" action="{{ route('admin.users') }}" class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="fh-search">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text" name="search" placeholder="Поиск по имени, email или никнейму" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select fh-form-control" onchange="this.form.submit()">
                    <option value="">Все роли</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Пользователь</option>
                    <option value="master" {{ request('role') == 'master' ? 'selected' : '' }}>Мастер</option>
                    <option value="organizer" {{ request('role') == 'organizer' ? 'selected' : '' }}>Организатор</option>
                    <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>Модератор</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Администратор</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="verified" class="form-select fh-form-control" onchange="this.form.submit()">
                    <option value="">Все статусы верификации</option>
                    <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Верифицированные</option>
                    <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Не верифицированные</option>
                </select>
            </div>
        </form>

        <!-- Таблица -->
        <div class="fh-table">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Верификация</th>
                        <th>Ограничения</th>
                        <th>Статус</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $user->avatar_url }}" class="fh-avatar" alt="">
                                <div>
                                    <div style="font-weight:600;">{{ $user->name }}</div>
                                    @if($user->nickname)
                                        <small class="text-muted">«{{ $user->nickname }}»</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="fh-badge fh-badge-primary">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>
                            @if($user->is_verified)
                                <span class="fh-status-online">
                                    <i class="bi bi-patch-check-fill me-1"></i>Верифицирован
                                </span>
                            @else
                                <span class="fh-status-pending">Не верифицирован</span>
                            @endif
                        </td>
                        <td>
                            @if($user->hasRestrictions())
                                <span class="fh-badge" style="background:rgba(196,92,92,0.12); color:var(--fh-danger);">
                                    <i class="bi bi-shield-x me-1"></i>{{ count($user->getRestrictionsList()) }} ограничений
                                </span>
                            @else
                                <span class="text-muted" style="font-size:0.85rem;">Нет</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="fh-status-online">Активен</span>
                            @else
                                <span class="fh-status-pending">Деактивирован</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d.m.Y') }}</td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <!-- Редактировать -->
                                <a href="{{ route('admin.users.edit', $user) }}" class="fh-btn fh-btn-ghost" style="padding:6px 10px; font-size:0.8rem;" title="Редактировать">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <!-- Верификация -->
                                @if(!$user->is_verified)
                                    <form method="POST" action="{{ route('admin.users.verify', $user) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="fh-btn fh-btn-ghost" style="padding:6px 10px; font-size:0.8rem; color:var(--fh-success);" title="Верифицировать">
                                            <i class="bi bi-patch-check-fill"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.unverify', $user) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="fh-btn fh-btn-ghost" style="padding:6px 10px; font-size:0.8rem; color:var(--fh-warning);" title="Отменить верификацию">
                                            <i class="bi bi-patch-exclamation-fill"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- Ограничения -->
                                <a href="{{ route('admin.users.restrict', $user) }}" class="fh-btn fh-btn-ghost" style="padding:6px 10px; font-size:0.8rem; {{ $user->hasRestrictions() ? 'color:var(--fh-danger);' : 'color:var(--fh-warning);' }}" title="Управление ограничениями">
                                    <i class="bi bi-shield-x"></i>
                                </a>

                                <!-- Снять ограничения (если есть) -->
                                @if($user->hasRestrictions())
                                    <form method="POST" action="{{ route('admin.users.remove-restrictions', $user) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="fh-btn fh-btn-ghost" style="padding:6px 10px; font-size:0.8rem; color:var(--fh-success);" title="Снять все ограничения" onclick="return confirm('Снять все ограничения с пользователя?')">
                                            <i class="bi bi-shield-check"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- Деактивировать (не для админов) -->
                                @if(!$user->isAdmin())
                                    <form method="POST" action="{{ route('admin.users.delete', $user) }}" style="display:inline;" onsubmit="return confirm('Деактивировать пользователя?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="fh-btn fh-btn-ghost" style="padding:6px 10px; font-size:0.8rem; color:var(--fh-danger);" title="Деактивировать">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Пользователи не найдены</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</section>
@endsection