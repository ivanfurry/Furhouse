@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="admin-users-header">
            <div>
                <span class="fh-section-label">АДМИН-ПАНЕЛЬ</span>
                <h1 class="admin-users-title">Пользователи</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
                <i class="bi bi-arrow-left"></i>Назад
            </a>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.users') }}" class="admin-users-filters">
            <div class="admin-search-box">
                <i class="bi bi-search text-muted"></i>
                <input type="text" name="search" placeholder="Поиск по имени, email или никнейму" value="{{ request('search') }}">
            </div>
            <select name="role" class="admin-filter-select" onchange="this.form.submit()">
                <option value="">Все роли</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Пользователь</option>
                <option value="master" {{ request('role') == 'master' ? 'selected' : '' }}>Мастер</option>
                <option value="organizer" {{ request('role') == 'organizer' ? 'selected' : '' }}>Организатор</option>
                <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>Модератор</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Администратор</option>
            </select>
            <select name="verified" class="admin-filter-select" onchange="this.form.submit()">
                <option value="">Все статусы верификации</option>
                <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Верифицированные</option>
                <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Не верифицированные</option>
            </select>
        </form>

        <!-- Table -->
        <div class="admin-users-table">
            <table>
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
                            <div class="admin-user-cell">
                                <img src="{{ $user->avatar_url }}" class="admin-user-avatar" alt="">
                                <div>
                                    <div class="admin-user-name">{{ $user->name }}</div>
                                    @if($user->nickname)
                                        <div class="admin-user-nickname">«{{ $user->nickname }}»</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="admin-role-badge admin-role-badge--{{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            @if($user->is_verified)
                                <span class="admin-verify-badge admin-verify-badge--verified">
                                    <i class="bi bi-patch-check-fill"></i>Верифицирован
                                </span>
                            @else
                                <span class="admin-verify-badge admin-verify-badge--unverified">
                                    <i class="bi bi-patch-exclamation-fill"></i>Не верифицирован
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->hasRestrictions())
                                <span class="admin-restrictions-badge admin-restrictions-badge--active">
                                    <i class="bi bi-shield-x"></i>{{ count($user->getRestrictionsList()) }}
                                </span>
                            @else
                                <span class="admin-restrictions-badge admin-restrictions-badge--none">Нет</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="admin-status-badge admin-status-badge--active">Активен</span>
                            @else
                                <span class="admin-status-badge admin-status-badge--inactive">Деактивирован</span>
                            @endif
                        </td>
                        <td class="admin-date-cell">{{ $user->created_at->format('d.m.Y') }}</td>
                        <td>
                            <div class="admin-actions">
                                <a href="{{ route('admin.users.edit', $user) }}" class="admin-action-btn admin-action-btn--edit" title="Редактировать">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                @if(!$user->is_verified)
                                    <form method="POST" action="{{ route('admin.users.verify', $user) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="admin-action-btn admin-action-btn--verify" title="Верифицировать">
                                            <i class="bi bi-patch-check-fill"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.unverify', $user) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="admin-action-btn admin-action-btn--unverify" title="Отменить верификацию">
                                            <i class="bi bi-patch-exclamation-fill"></i>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.users.restrict', $user) }}" 
                                   class="admin-action-btn {{ $user->hasRestrictions() ? 'admin-action-btn--remove-restrict' : 'admin-action-btn--restrict' }}" 
                                   title="{{ $user->hasRestrictions() ? 'Снять ограничения' : 'Наложить ограничения' }}">
                                    <i class="bi bi-{{ $user->hasRestrictions() ? 'shield-check' : 'shield-x' }}"></i>
                                </a>

                                @if(!$user->isAdmin())
                                    <form method="POST" action="{{ route('admin.users.delete', $user) }}" style="display:inline;" onsubmit="return confirm('Деактивировать пользователя?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action-btn admin-action-btn--delete" title="Деактивировать">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="admin-empty-state">
                                <i class="bi bi-people"></i>
                                <p>Пользователи не найдены</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="admin-pagination">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</section>
@endsection