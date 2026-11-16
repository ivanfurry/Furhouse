<?php

namespace App\Http\Controllers;

use App\Models\ForumBoard;
use App\Models\ForumPost;
use App\Models\ForumThread;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $boards = ForumBoard::active()->ordered()->get();

        $threadsQuery = ForumThread::with(['user', 'board'])
            ->where('status', 'approved') // ← добавить
            ->orderBy('is_pinned', 'desc')
            ->orderBy('last_activity_at', 'desc');

        if ($request->filled('board')) {
            $threadsQuery->where('board_id', $request->board);
        }

        if ($request->filled('search')) {
            $threadsQuery->where('title', 'like', "%{$request->search}%");
        }

        $threads = $threadsQuery->paginate(10)->withQueryString();

        $onlineCount = 248; // Could be cached from sessions
        $newThreadsToday = ForumThread::whereDate('created_at', today())->count();

        return view('forum.index', compact(
            'boards',
            'threads',
            'onlineCount',
            'newThreadsToday'
        ));
    }

    public function show(ForumThread $thread)
    {
        $thread->incrementViews();
        $thread->load(['user', 'board', 'posts.user']);

        $posts = $thread->posts()->with('user')->orderBy('created_at')->paginate(20);

        return view('forum.show', compact('thread', 'posts'));
    }

    public function create(Request $request)
    {
        $boards = ForumBoard::active()->ordered()->get();
        $selectedBoard = $request->get('board');

        return view('forum.create', compact('boards', 'selectedBoard'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'board_id' => 'required|exists:forum_boards,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:10',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);
        $validated['last_activity_at'] = now();
        
        // ИСПРАВЛЕНО: статус pending — требует модерации
        $validated['status'] = 'pending';

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('forum', 'public');
        }

        $thread = ForumThread::create($validated);

        // Update board counters
        $thread->board->increment('threads_count');
        $thread->board->increment('posts_count');

        \App\Models\ActivityLog::log('thread_created', $thread);

        return redirect()->route('forum.index')
            ->with('success', 'Тред отправлен на модерацию!');
    }

    // Добавить метод модерации
    public function moderateThread(Request $request, ForumThread $thread)
    {
        if (!auth()->user()->isModerator()) {
            abort(403);
        }

        $action = $request->input('action'); // 'approve' или 'reject'

        if ($action === 'approve') {
            $thread->update(['status' => 'approved']);
            $thread->board->increment('threads_count');
            \App\Models\ActivityLog::log('thread_approved', $thread);
            return back()->with('success', 'Тред одобрен.');
        } elseif ($action === 'reject') {
            $thread->update(['status' => 'rejected']);
            $thread->board->decrement('threads_count');
            \App\Models\ActivityLog::log('thread_rejected', $thread);
            return back()->with('success', 'Тред отклонён.');
        }

        return back()->with('error', 'Неверное действие.');
    }

    public function storePost(Request $request, ForumThread $thread)
    {
        if ($thread->is_locked) {
            return back()->with('error', 'Тред заблокирован.');
        }

        $validated = $request->validate([
            'body' => 'required|string|min:5',
        ]);

        $validated['thread_id'] = $thread->id;
        $validated['user_id'] = auth()->id();

        ForumPost::create($validated);

        $thread->increment('replies_count');
        $thread->update(['last_activity_at' => now()]);
        $thread->board->increment('posts_count');

        return back()->with('success', 'Ответ опубликован!');
    }

    public function togglePin(ForumThread $thread)
    {
        if (!auth()->user()->isModerator()) {
            abort(403);
        }

        $thread->update(['is_pinned' => !$thread->is_pinned]);

        return back()->with('success', 'Статус закреплённого изменён.');
    }

    public function toggleLock(ForumThread $thread)
    {
        if (!auth()->user()->isModerator()) {
            abort(403);
        }

        $thread->update(['is_locked' => !$thread->is_locked]);

        return back()->with('success', 'Тред ' . ($thread->is_locked ? 'заблокирован' : 'разблокирован') . '.');
    }
}