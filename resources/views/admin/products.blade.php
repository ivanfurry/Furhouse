@extends('layouts.app')

@section('title', 'Товары')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="fh-section-label">АДМИН-ПАНЕЛЬ</span>
                <h1 class="fh-section-title">Товары</h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="fh-btn fh-btn-outline">
                <i class="bi bi-arrow-left me-2"></i>Назад
            </a>
        </div>

        <form method="GET" action="{{ route('admin.products') }}" class="mb-4">
            <select name="status" class="form-select fh-form-control" style="max-width:300px;" onchange="this.form.submit()">
                <option value="">Все статусы</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Черновики</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Проданные</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Архив</option>
            </select>
        </form>

        <div class="fh-table">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Автор</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/60' }}" 
                                     style="width:50px; height:50px; border-radius:8px; object-fit:cover;">
                                <div>
                                    <div style="font-weight:600;">{{ $product->title }}</div>
                                    <small class="text-muted">{{ $product->created_at->format('d.m.Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->user->name }}</td>
                        <td>{{ $product->category->name ?? '—' }}</td>
                        <td><strong>{{ $product->formatted_price }}</strong></td>
                        <td>
                            @if($product->status === 'active')
                                <span class="fh-status-online">Активен</span>
                            @elseif($product->status === 'draft')
                                <span class="fh-status-pending">На проверке</span>
                            @else
                                <span class="fh-badge" style="background:var(--fh-bg); color:var(--fh-text-muted);">{{ $product->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($product->status === 'draft')
                                    <form method="POST" action="{{ route('admin.products.moderate', $product) }}">
                                        @csrf
                                        <button type="submit" class="fh-btn fh-btn-primary" style="padding:6px 12px; font-size:0.85rem;">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.products.reject', $product) }}">
                                        @csrf
                                        <button type="submit" class="fh-btn fh-btn-ghost" style="padding:6px 12px; font-size:0.85rem; color:var(--fh-danger);">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('products.show', $product) }}" class="fh-btn fh-btn-ghost" style="padding:6px 12px; font-size:0.85rem;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Товары не найдены</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</section>
@endsection