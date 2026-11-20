/**
 * FurHouse - Global JavaScript
 * Общий файл скриптов для всего сайта
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. CSRF Token для AJAX запросов
    // ==========================================
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) {
        const token = meta.getAttribute('content');
        if (window.axios) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        }
    }

    // ==========================================
    // 2. Авто-скрытие тостов (уведомлений)
    // ==========================================
    setTimeout(function() {
        const toasts = document.querySelectorAll('.fh-toast');
        toasts.forEach(function(toast) {
            toast.style.transition = 'opacity 0.5s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        });
    }, 4000);

    // ==========================================
    // 3. Авто-сабмит фильтров в каталоге
    // ==========================================
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.addEventListener('change', () => {
                filterForm.submit();
            });
        });
    }

    // ==========================================
    // 4. Избранное (toggleFavorite)
    // ==========================================
    window.toggleFavorite = function(productId, btn) {
        if (!btn) {
            btn = event.currentTarget;
        }
        
        fetch(`/products/${productId}/favorite`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const icon = btn.querySelector('i');
            if (data.favorited) {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
                btn.classList.add('active');
            } else {
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
                btn.classList.remove('active');
            }
        })
        .catch(err => console.error('Ошибка добавления в избранное:', err));
    };

    // ==========================================
    // 5. Добавление в корзину (addToCart)
    // ==========================================
    window.addToCart = function(productId, btn) {
        if (!btn) {
            btn = event.currentTarget;
        }
        
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Визуальная обратная связь
                if (btn) {
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('bi-bag');
                        icon.classList.add('bi-bag-check-fill');
                        btn.style.background = 'var(--fh-success)';
                        btn.style.color = 'white';
                        
                        setTimeout(() => {
                            icon.classList.remove('bi-bag-check-fill');
                            icon.classList.add('bi-bag');
                            btn.style.background = '';
                            btn.style.color = '';
                        }, 1500);
                    }
                }
                
                // Обновляем счётчик в navbar
                updateCartCounter();
            }
        })
        .catch(err => console.error('Ошибка добавления в корзину:', err));
    };

    // ==========================================
    // 6. Обновление счётчика корзины
    // ==========================================
    window.updateCartCounter = function() {
        fetch('/cart/count', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.cart-badge');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'block' : 'none';
            }
        })
        .catch(err => console.error('Ошибка обновления счётчика:', err));
    };

    // ==========================================
    // 7. Удаление из избранного (для профиля)
    // ==========================================
    window.removeFavorite = function(favoriteId) {
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
        })
        .catch(err => console.error('Ошибка удаления из избранного:', err));
    };

    // ==========================================
    // 8. Плавная прокрутка для якорных ссылок
    // ==========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#' && targetId.length > 1) {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // ==========================================
    // 9. Подтверждение удаления
    // ==========================================
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function(e) {
            if (!confirm(this.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

    console.log('FurHouse JS loaded ✓');
});