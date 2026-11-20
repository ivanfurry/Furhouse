<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceDiscussionController;
use App\Http\Controllers\SupportChatController;
use App\Http\Controllers\SupportTicketController;
use Illuminate\Support\Facades\Route;

// ==========================================
// ПУБЛИЧНЫЕ МАРШРУТЫ (без авторизации)
// ==========================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Товары
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Встречи
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Форум
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');

// Команда
Route::get('/team', [TeamController::class, 'index'])->name('team');

// Правила и безопасность
Route::get('/rules', [HomeController::class, 'rules'])->name('rules');

// Мастера
Route::get('/masters', [MasterController::class, 'index'])->name('masters.index');

// ==========================================
// ГОСТЕВЫЕ МАРШРУТЫ (только для неавторизованных)
// ==========================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ==========================================
// АВТОРИЗОВАННЫЕ МАРШРУТЫ
// ==========================================

Route::middleware('auth')->group(function () {
    
    // Выход
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Профиль
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password');
   
    // Публичный профиль пользователя
    Route::get('/users/{user}', [AuthController::class, 'showPublicProfile'])->name('users.show');

    // ----- ТОВАРЫ: создание (ДО маршрутов с параметром!) -----
    Route::middleware('master')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    });

    // ----- ВСТРЕЧИ: создание (ДО маршрутов с параметром!) -----
    Route::middleware('organizer')->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
    });

    // ----- ФОРУМ: создание -----
    Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');

    // ----- МАСТЕРА: создание услуг (ДО маршрутов с параметром!) -----
    Route::middleware('master')->group(function () {
        Route::get('/masters/create', [MasterController::class, 'create'])->name('masters.create');
        Route::post('/masters', [MasterController::class, 'store'])->name('masters.store');
    });

    // ----- ТОВАРЫ: маршруты с параметром {product} -----
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/favorite', [ProductController::class, 'toggleFavorite'])->name('products.favorite');

    // ----- ВСТРЕЧИ: маршруты с параметром {event} -----
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/participate', [EventController::class, 'participate'])->name('events.participate');
    Route::post('/events/{event}/cancel', [EventController::class, 'cancelParticipation'])->name('events.cancel');

    // ----- ФОРУМ: маршруты с параметром {thread} -----
    Route::get('/forum/thread/{thread}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum/thread/{thread}/post', [ForumController::class, 'storePost'])->name('forum.post');
    Route::post('/forum/thread/{thread}/pin', [ForumController::class, 'togglePin'])->name('forum.pin')->middleware('moderator');
    Route::post('/forum/thread/{thread}/lock', [ForumController::class, 'toggleLock'])->name('forum.lock')->middleware('moderator');

    // ----- МАСТЕРА: маршруты с параметром {service} -----
    Route::get('/masters/{service}', [MasterController::class, 'show'])->name('masters.show');


    // Обращения к персоналу
    Route::get('/support', [SupportTicketController::class, 'index'])->name('support.index');
    Route::get('/support/create', [SupportTicketController::class, 'create'])->name('support.create');
    Route::post('/support', [SupportTicketController::class, 'store'])->name('support.store');
    Route::get('/support/{ticket}', [SupportTicketController::class, 'show'])->name('support.show');
    Route::post('/support/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('support.reply')->middleware('moderator');

    // Удаление из избранного
    Route::delete('/favorites/{favorite}', [AuthController::class, 'removeFavorite'])->name('favorites.remove');

    // Корзина
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/checkout', [CartController::class, 'placeOrder'])->name('cart.placeOrder');

    // Заказы
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Чат обращений для пользователей
    Route::get('/support/chat', [SupportChatController::class, 'indexForUser'])->name('support.chat.index');
    Route::get('/support/chat/{ticket}', [SupportChatController::class, 'show'])->name('support.chat.show');
    Route::post('/support/chat/{ticket}/message', [SupportChatController::class, 'sendMessage'])->name('support.chat.message');

    // Чат обращений для персонала
    Route::get('/staff/support', [SupportChatController::class, 'indexForStaff'])->name('staff.support.index')->middleware('moderator');
    Route::post('/staff/support/{ticket}/assign', [SupportChatController::class, 'assignToMe'])->name('staff.support.assign')->middleware('moderator');
    Route::post('/staff/support/{ticket}/close', [SupportChatController::class, 'closeTicket'])->name('staff.support.close')->middleware('moderator');

    // Обсуждение услуг с мастерами
    Route::get('/services/{service}/discuss', [ServiceDiscussionController::class, 'create'])->name('service-discussion.create');
    Route::post('/services/{service}/discuss', [ServiceDiscussionController::class, 'store'])->name('service-discussion.store');
    Route::get('/service-discussions', [ServiceDiscussionController::class, 'indexForMaster'])->name('service-discussion.master-index');
    Route::get('/service-discussions/{ticket}', [ServiceDiscussionController::class, 'show'])->name('service-discussion.show');
    Route::post('/service-discussions/{ticket}/message', [ServiceDiscussionController::class, 'sendMessage'])->name('service-discussion.message');
    Route::post('/service-discussions/{ticket}/close', [ServiceDiscussionController::class, 'close'])->name('service-discussion.close');
});

// ==========================================
// АДМИН-ПАНЕЛЬ
// ==========================================

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Пользователи
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/verify', [AdminController::class, 'verifyUser'])->name('users.verify');
        Route::post('/users/{user}/unverify', [AdminController::class, 'unverifyUser'])->name('users.unverify');
        Route::get('/users/{user}/restrict', [AdminController::class, 'showRestrictForm'])->name('users.restrict');
        Route::post('/users/{user}/restrict', [AdminController::class, 'restrictUser'])->name('users.restrict.store');
        Route::post('/users/{user}/remove-restrictions', [AdminController::class, 'removeRestrictions'])->name('users.remove-restrictions');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        // Товары
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::post('/products/{product}/moderate', [AdminController::class, 'moderateProduct'])->name('products.moderate');
        Route::post('/products/{product}/reject', [AdminController::class, 'rejectProduct'])->name('products.reject');
        
        // Встречи
        Route::get('/events', [AdminController::class, 'events'])->name('events');
        Route::post('/events/{event}/moderate', [AdminController::class, 'moderateEvent'])->name('events.moderate');
        
        // Обращения
        Route::get('/tickets', [AdminController::class, 'tickets'])->name('tickets');
        Route::post('/tickets/{ticket}/assign', [AdminController::class, 'assignTicket'])->name('tickets.assign');
        Route::post('/tickets/{ticket}/resolve', [AdminController::class, 'resolveTicket'])->name('tickets.resolve');
        
        // Услуги мастеров
        Route::get('/services', [AdminController::class, 'services'])->name('services');
        Route::post('/services/{service}/moderate', [AdminController::class, 'moderateService'])->name('services.moderate');
        
        // Форум
        Route::get('/forum', [AdminController::class, 'forumThreads'])->name('forum');
        Route::post('/forum/{thread}/moderate', [AdminController::class, 'moderateThread'])->name('forum.moderate');
        
        // Активность и настройки
        Route::get('/activity', [AdminController::class, 'activityLog'])->name('activity');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

        // Объявления команде
        Route::get('/announcements', [AdminController::class, 'announcements'])->name('announcements');
        Route::get('/announcements/create', [AdminController::class, 'createAnnouncement'])->name('announcements.create');
        Route::post('/announcements', [AdminController::class, 'storeAnnouncement'])->name('announcements.store');
        Route::delete('/announcements/{announcement}', [AdminController::class, 'destroyAnnouncement'])->name('announcements.destroy');
    });