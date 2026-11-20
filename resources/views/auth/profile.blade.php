@extends('layouts.app')

@section('title', 'Мой профиль')

@section('content')
<section class="py-5">
    <div class="container">
        <span class="profile-header-label">ЛИЧНЫЙ КАБИНЕТ</span>
        <h1 class="fh-section-title" style="margin-bottom: 32px;">Мой профиль</h1>

        <div class="profile-layout">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-avatar-wrap">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                </div>
                <div class="profile-name">{{ auth()->user()->display_name }}</div>
                @if(auth()->user()->nickname)
                    <div class="profile-nickname">{{ '@' . auth()->user()->nickname }}</div>
                @endif
                <div class="profile-role-badge">
                    @if(auth()->user()->role === 'admin')
                        Администратор
                    @elseif(auth()->user()->role === 'moderator')
                        Модератор
                    @elseif(auth()->user()->role === 'master')
                        Мастер
                    @elseif(auth()->user()->role === 'organizer')
                        Организатор
                    @else
                        Участник FurHouse
                    @endif
                </div>
                <div class="profile-joined">В сообществе с {{ auth()->user()->created_at->format('M Y') }}</div>

                <button class="fh-btn fh-btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    Редактировать профиль
                </button>
                <button class="fh-btn fh-btn-ghost" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    Сменить пароль
                </button>

                {{-- Блок ограничений --}}
                @if(auth()->user()->isRestricted())
                <div class="alert alert-warning fh-alert mt-3" style="text-align: left;">
                    <strong><i class="bi bi-exclamation-triangle me-2"></i>На вашем аккаунте действуют ограничения:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach(auth()->user()->getRestrictionsList() as $restriction)
                            <li>{{ $restriction }}</li>
                        @endforeach
                    </ul>
                    @if(auth()->user()->restriction_reason)
                        <div class="mt-2" style="font-size: 0.85rem;">
                            <strong>Причина:</strong> {{ auth()->user()->restriction_reason }}
                        </div>
                    @endif
                    @if(auth()->user()->restriction_until)
                        <div style="font-size: 0.85rem;">
                            <strong>До:</strong> {{ auth()->user()->getRestrictionEndDate() }}
                        </div>
                    @else
                        <div style="font-size: 0.85rem;">
                            <strong>Срок:</strong> Бессрочно
                        </div>
                    @endif
                </div>
                @endif

                {{-- Активные обращения --}}
                @php
                    $activeTickets = auth()->user()->supportTickets()
                        ->where('status', '!=', 'resolved')
                        ->with(['messages' => function($q) {
                            $q->latest()->limit(1);
                        }])
                        ->orderBy('updated_at', 'desc')
                        ->take(3)
                        ->get();
                @endphp
                @if($activeTickets->count() > 0)
                <div class="profile-tickets-block mt-4">
                    <div class="profile-tickets-title">Активные обращения</div>
                    @foreach($activeTickets as $ticket)
                    <a href="{{ route('support.chat.show', $ticket) }}" class="profile-ticket-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="profile-ticket-name">{{ Str::limit($ticket->subject, 25) }}</span>
                            <span class="profile-ticket-status" style="background:{{ $ticket->status_color }}20; color:{{ $ticket->status_color }};">
                                {{ $ticket->status_label }}
                            </span>
                        </div>
                        @if($ticket->messages->count() > 0)
                            <div class="profile-ticket-preview">
                                {{ Str::limit($ticket->messages->first()->message, 40) }}
                            </div>
                        @endif
                    </a>
                    @endforeach
                    <a href="{{ route('support.chat.index') }}" class="profile-tickets-all">
                        Все обращения <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                @endif

                <div class="profile-privacy-note">
                    <strong>Только для вас</strong>
                    <small>Почта скрыта от других участников.</small>
                </div>
            </div>

            <!-- Main Content -->
            <div class="profile-main">
                <!-- Personal Info -->
                <div class="profile-section">
                    <h2 class="profile-section-title">Личные данные</h2>
                    <div class="profile-info-grid">
                        <div class="profile-info-item">
                            <label>Имя</label>
                            <div class="value">{{ auth()->user()->name }}</div>
                        </div>
                        <div class="profile-info-item">
                            <label>Почта</label>
                            <div class="value">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="profile-info-item">
                            <label>Город</label>
                            <div class="value">{{ auth()->user()->city ?? 'Не указан' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Favorites -->
                <div class="profile-section">
                    <div class="favorites-header">
                        <i class="bi bi-heart" style="font-size: 1.3rem; color: var(--fh-primary);"></i>
                        <h2>Избранное</h2>
                        <span class="count">{{ $favoritesCount }} сохранённых</span>
                    </div>

                    <div class="favorites-tabs">
                        <a href="{{ route('profile', ['tab' => 'all']) }}" class="favorites-tab {{ $activeTab === 'all' ? 'active' : '' }}">
                            Все · {{ $favoritesCount }}
                        </a>
                        <a href="{{ route('profile', ['tab' => 'products']) }}" class="favorites-tab {{ $activeTab === 'products' ? 'active' : '' }}">
                            Товары · {{ $productsCount }}
                        </a>
                        <a href="{{ route('profile', ['tab' => 'services']) }}" class="favorites-tab {{ $activeTab === 'services' ? 'active' : '' }}">
                            Услуги · {{ $servicesCount }}
                        </a>
                    </div>

                    <div class="favorites-grid">
                        @forelse($favorites as $fav)
                            @if($fav->favoritable_type === 'App\\Models\\Product')
                                <a href="{{ route('products.show', $fav->favoritable) }}" class="favorite-card">
                                    <div class="favorite-card-img">
                                        <img src="{{ $fav->favoritable->image ? asset('storage/' . $fav->favoritable->image) : 'https://via.placeholder.com/400x300/e8e0f0/7c5c9c' }}" alt="{{ $fav->favoritable->title }}">
                                        <button class="remove-fav" onclick="event.preventDefault(); removeFavorite({{ $fav->id }})" title="Удалить из избранного">
                                            <i class="bi bi-heart-fill"></i>
                                        </button>
                                    </div>
                                    <div class="favorite-card-body">
                                        <div class="favorite-card-type">Товар</div>
                                        <div class="favorite-card-title">{{ $fav->favoritable->title }}</div>
                                        <div class="favorite-card-author">от {{ $fav->favoritable->user->name }}</div>
                                        <div class="favorite-card-price">{{ $fav->favoritable->formatted_price }}</div>
                                    </div>
                                </a>
                            @elseif($fav->favoritable_type === 'App\\Models\\Service')
                                <a href="{{ route('masters.show', $fav->favoritable) }}" class="favorite-card">
                                    <div class="favorite-card-img">
                                        <img src="https://via.placeholder.com/400x300/e8e0f0/7c5c9c?text={{ urlencode($fav->favoritable->title) }}" alt="{{ $fav->favoritable->title }}">
                                        <button class="remove-fav" onclick="event.preventDefault(); removeFavorite({{ $fav->id }})" title="Удалить из избранного">
                                            <i class="bi bi-heart-fill"></i>
                                        </button>
                                    </div>
                                    <div class="favorite-card-body">
                                        <div class="favorite-card-type">Услуга</div>
                                        <div class="favorite-card-title">{{ $fav->favoritable->title }}</div>
                                        <div class="favorite-card-author">от {{ $fav->favoritable->user->name }}</div>
                                        <div class="favorite-card-price">{{ $fav->favoritable->formatted_price }}</div>
                                    </div>
                                </a>
                            @endif
                        @empty
                            <div class="favorites-empty" style="grid-column: 1 / -1;">
                                <i class="bi bi-heart"></i>
                                <p>У вас пока нет избранных товаров или услуг</p>
                            </div>
                        @endforelse
                    </div>

                    <p class="favorites-hint">Нажмите на сердечко в каталоге, чтобы сохранить товар или услугу.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Edit Profile Modal -->
<div class="modal fade profile-modal" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактировать профиль</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="avatar-upload">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar">
                        <div class="avatar-upload-btn">
                            <label class="fh-btn fh-btn-outline" style="cursor: pointer;">
                                Изменить аватар
                                <input type="file" name="avatar" accept="image/*" style="display: none;">
                            </label>
                            <small>JPG или PNG · до 5 МБ</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Имя</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Почта</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Никнейм</label>
                        <input type="text" name="nickname" class="form-control" value="{{ old('nickname', auth()->user()->nickname) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Город</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', auth()->user()->city) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">О себе</label>
                        <textarea name="bio" class="form-control" rows="3">{{ old('bio', auth()->user()->bio) }}</textarea>
                    </div>

                    <p class="form-hint">Изменения появятся в профиле после сохранения.</p>

                    <div class="modal-footer">
                        <button type="submit" class="fh-btn fh-btn-primary">Сохранить изменения</button>
                        <button type="button" class="fh-btn fh-btn-ghost" data-bs-dismiss="modal">Отменить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade profile-modal" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Сменить пароль</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Текущий пароль</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Новый пароль</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Повторите новый пароль</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <p class="form-hint">Не менее 8 символов: буквы и цифры.</p>
                    <p class="form-hint">Текущий пароль нужен для защиты аккаунта.</p>

                    <div class="modal-footer">
                        <button type="submit" class="fh-btn fh-btn-primary">Сохранить пароль</button>
                        <button type="button" class="fh-btn fh-btn-ghost" data-bs-dismiss="modal">Отменить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function removeFavorite(favoriteId) {
    if (!confirm('Удалить из избранного?')) return;

    fetch(`/favorites/${favoriteId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush