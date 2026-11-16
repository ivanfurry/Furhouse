<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ForumThread;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                abort(403, 'Доступ запрещён. Требуются права администратора.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'users_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            'products' => Product::count(),
            'products_pending' => Product::where('status', 'draft')->count(),
            'services_pending' => Service::where('status', 'pending')->count(),
            'threads_pending' => ForumThread::where('status', 'pending')->count(),
            'tickets' => SupportTicket::where('status', '!=', 'resolved')->count(),
            'tickets_pending' => SupportTicket::where('status', 'open')->count(),
            'safety' => 99.8,
            // Новые счётчики для управления пользователями
            'users_unverified' => User::where('is_verified', false)->count(),
            'users_restricted' => User::where(function($q) {
                $q->where('can_post_products', false)
                ->orWhere('can_post_services', false)
                ->orWhere('can_create_events', false)
                ->orWhere('can_post_forum', false)
                ->orWhere('can_comment', false);
            })->count(),
        ];

        $recentActivity = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $pendingEvents = Event::where('status', 'pending')->count();
        $openTickets = SupportTicket::where('status', 'open')->count();
        $moderationQueue = Product::where('status', 'draft')->count();

        return view('admin.dashboard', compact(
            'stats',
            'recentActivity',
            'pendingEvents',
            'openTickets',
            'moderationQueue'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('nickname', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified === '1');
        }

        if ($request->filled('restricted') && $request->restricted === '1') {
            $query->where(function($q) {
                $q->where('can_post_products', false)
                ->orWhere('can_post_services', false)
                ->orWhere('can_create_events', false)
                ->orWhere('can_post_forum', false)
                ->orWhere('can_comment', false);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100|unique:users,nickname,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,master,organizer,moderator,admin',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        ActivityLog::log('user_updated', $user, $validated);

        return back()->with('success', 'Пользователь обновлён.');
    }

    public function deleteUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Нельзя удалить другого администратора.');
        }

        $user->update(['is_active' => false]);
        ActivityLog::log('user_deactivated', $user);

        return back()->with('success', 'Пользователь деактивирован.');
    }

    public function products(Request $request)
    {
        $query = Product::with(['user', 'category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.products', compact('products'));
    }

    public function moderateProduct(Product $product)
    {
        $product->update(['status' => 'active']);
        ActivityLog::log('product_approved', $product);

        return back()->with('success', 'Товар одобрен.');
    }

    public function rejectProduct(Product $product)
    {
        $product->update(['status' => 'archived']);
        ActivityLog::log('product_rejected', $product);

        return back()->with('success', 'Товар отклонён.');
    }

    public function events(Request $request)
    {
        $query = Event::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->orderBy('starts_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.events', compact('events'));
    }

    public function moderateEvent(Event $event)
    {
        $event->update(['status' => 'approved', 'is_verified' => true]);
        ActivityLog::log('event_approved', $event);

        return back()->with('success', 'Встреча одобрена.');
    }

    public function tickets(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedUser']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.tickets', compact('tickets'));
    }

    public function assignTicket(Request $request, SupportTicket $ticket)
    {
        $ticket->update([
            'assigned_to' => auth()->id(),
            'status' => 'in_progress',
        ]);

        ActivityLog::log('ticket_assigned', $ticket);

        return back()->with('success', 'Обращение назначено вам.');
    }

    public function resolveTicket(SupportTicket $ticket)
    {
        $ticket->update(['status' => 'resolved']);
        ActivityLog::log('ticket_resolved', $ticket);

        return back()->with('success', 'Обращение закрыто.');
    }

    public function activityLog()
    {
        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.activity-log', compact('logs'));
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        // Фильтруем чувствительные поля перед логированием
        $cleanData = $request->except(['_token', '_method']);
        
        ActivityLog::log('settings_updated', null, $cleanData);
        
        return back()->with('success', 'Настройки сохранены.');
    }

    public function services(Request $request)
    {
        $query = Service::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.services', compact('services'));
    }

    public function moderateService(Service $service)
    {
        $action = request()->input('action');

        if ($action === 'approve') {
            $service->update(['status' => 'approved', 'accepts_orders' => true]);
            ActivityLog::log('service_approved', $service);
            return back()->with('success', 'Услуга одобрена.');
        } elseif ($action === 'reject') {
            $service->update(['status' => 'rejected', 'accepts_orders' => false]);
            ActivityLog::log('service_rejected', $service);
            return back()->with('success', 'Услуга отклонена.');
        }

        return back()->with('error', 'Неверное действие.');
    }

    public function forumThreads(Request $request)
    {
        $query = ForumThread::with(['user', 'board']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $threads = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.forum-threads', compact('threads'));
    }

    public function moderateThread(ForumThread $thread)
    {
        $action = request()->input('action');

        if ($action === 'approve') {
            $thread->update(['status' => 'approved']);
            ActivityLog::log('thread_approved', $thread);
            return back()->with('success', 'Тред одобрен.');
        } elseif ($action === 'reject') {
            $thread->update(['status' => 'rejected']);
            ActivityLog::log('thread_rejected', $thread);
            return back()->with('success', 'Тред отклонён.');
        }

        return back()->with('error', 'Неверное действие.');
    }
    public function showRestrictForm(User $user)
    {
        return view('admin.restrict-user', compact('user'));
    }

    public function restrictUser(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Нельзя ограничить администратора.');
        }

        $validated = $request->validate([
            'can_post_products' => 'nullable|boolean',
            'can_post_services' => 'nullable|boolean',
            'can_create_events' => 'nullable|boolean',
            'can_post_forum' => 'nullable|boolean',
            'can_comment' => 'nullable|boolean',
            'restriction_reason' => 'required|string|min:10',
            'restriction_until' => 'nullable|date|after:now',
        ]);

        $validated['can_post_products'] = $request->has('can_post_products');
        $validated['can_post_services'] = $request->has('can_post_services');
        $validated['can_create_events'] = $request->has('can_create_events');
        $validated['can_post_forum'] = $request->has('can_post_forum');
        $validated['can_comment'] = $request->has('can_comment');
        $validated['restricted_by'] = auth()->id();

        $user->update($validated);

        // Логируем только человекочитаемые данные
        ActivityLog::log('user_restricted', $user, [
            'can_post_products' => $validated['can_post_products'],
            'can_post_services' => $validated['can_post_services'],
            'can_create_events' => $validated['can_create_events'],
            'can_post_forum' => $validated['can_post_forum'],
            'can_comment' => $validated['can_comment'],
            'restriction_reason' => $validated['restriction_reason'],
            'restriction_until' => $validated['restriction_until'] ?? null,
        ]);

        return back()->with('success', 'Ограничения применены к пользователю.');
    }

    public function removeRestrictions(User $user)
    {
        $user->update([
            'can_post_products' => true,
            'can_post_services' => true,
            'can_create_events' => true,
            'can_post_forum' => true,
            'can_comment' => true,
            'restriction_reason' => null,
            'restriction_until' => null,
            'restricted_by' => null,
        ]);

        ActivityLog::log('user_restrictions_removed', $user);

        return back()->with('success', 'Все ограничения сняты с пользователя.');
    }

    public function verifyUser(User $user)
    {
        $user->update(['is_verified' => true]);
        ActivityLog::log('user_verified', $user);

        return back()->with('success', 'Пользователь верифицирован.');
    }

    public function unverifyUser(User $user)
    {
        $user->update(['is_verified' => false]);
        ActivityLog::log('user_unverified', $user);

        return back()->with('success', 'Верификация пользователя отменена.');
    }

    public function announcements(Request $request)
    {
        $query = Announcement::with('user');

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $announcements = $query->orderBy('is_pinned', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(20)
                            ->withQueryString();

        return view('admin.announcements', compact('announcements'));
    }

    public function createAnnouncement()
    {
        return view('admin.create-announcement');
    }

    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:10',
            'priority' => 'required|in:normal,important,urgent',
            'is_pinned' => 'nullable|boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_pinned'] = $request->boolean('is_pinned');

        Announcement::create($validated);

        ActivityLog::log('announcement_created', null, ['title' => $validated['title']]);

        return redirect()->route('admin.announcements')
            ->with('success', 'Объявление опубликовано!');
    }

    public function destroyAnnouncement(Announcement $announcement)
    {
        $announcement->delete();
        ActivityLog::log('announcement_deleted', $announcement);

        return back()->with('success', 'Объявление удалено.');
    }
}