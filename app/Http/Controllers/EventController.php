<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::where('status', 'approved')->with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%")
                  ->orWhere('location', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('region')) {
            $query->byCity($request->region);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('group_size')) {
            $query->where('max_guests', '<=', $request->group_size);
        }

        $query->orderBy('starts_at');

        $events = $query->paginate(12)->withQueryString();
        $featuredEvent = Event::featured()->upcoming()->with('user')->first();

        return view('events.index', compact('events', 'featuredEvent'));
    }

    public function show(Event $event)
    {
        $event->load(['user', 'participants.user']);
        $isParticipant = auth()->check() && $event->participants()->where('user_id', auth()->id())->exists();

        return view('events.show', compact('event', 'isParticipant'));
    }

    public function create()
    {
        $this->authorizeOrganizer();
        return view('events.create');
    }

    public function store(Request $request)
    {
        $this->authorizeOrganizer();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'type' => 'required|in:walk,picnic,creative,games,festival,other',
            'city' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'starts_at' => 'required|date|after:now',
            'ends_at' => 'nullable|date|after:starts_at',
            'max_guests' => 'nullable|integer|min:2',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);
        $validated['is_free'] = !($validated['price'] ?? 0) > 0;
        $validated['status'] = auth()->user()->isVerified() ? 'approved' : 'pending';

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Встреча создана!');
    }

    public function participate(Event $event)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Войдите для участия.');
        }

        $existing = $event->participants()->where('user_id', auth()->id())->first();

        if ($existing) {
            if ($existing->status === 'cancelled') {
                $existing->update(['status' => 'pending']);
                $event->increment('participants_count');
            }
            return back()->with('info', 'Вы уже участвуете в этой встрече.');
        }

        if ($event->max_guests && $event->participants_count >= $event->max_guests) {
            return back()->with('error', 'Достигнуто максимальное количество участников.');
        }

        $event->participants()->create([
            'user_id' => auth()->id(),
            'status' => 'confirmed',
        ]);
        $event->increment('participants_count');

        return back()->with('success', 'Вы записались на встречу!');
    }

    public function cancelParticipation(Event $event)
    {
        $participant = $event->participants()->where('user_id', auth()->id())->first();

        if ($participant) {
            $participant->update(['status' => 'cancelled']);
            $event->decrement('participants_count');
        }

        return back()->with('success', 'Участие отменено.');
    }

    private function authorizeOrganizer(): void
    {
        if (!auth()->user()->isOrganizer()) {
            abort(403, 'Только проверенные организаторы могут создавать встречи.');
        }
    }
}