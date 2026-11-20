<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Список обращений пользователя
    public function index()
    {
        $tickets = SupportTicket::where('user_id', auth()->id())
            ->with(['assignedUser', 'messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('support.index', compact('tickets'));
    }

    // Форма создания обращения
    public function create(Request $request)
    {
        $assignedTo = null;
        $serviceTitle = null;
        
        if ($request->filled('to')) {
            $assignedTo = User::whereIn('role', ['admin', 'moderator', 'master'])
                ->where('id', $request->to)
                ->first();
        }

        if ($request->filled('service')) {
            $service = \App\Models\Service::find($request->service);
            if ($service) {
                $serviceTitle = $service->title;
            }
        }

        return view('support.create', compact('assignedTo', 'serviceTitle'));
    }

    // Сохранение обращения
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'open',
            'priority' => $validated['priority'],
            'assigned_to' => $validated['assigned_to'] ?? null,
        ]);

        // Создаём первое сообщение
        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_staff' => false,
        ]);

        return redirect()->route('support.chat.show', $ticket)
            ->with('success', 'Обращение создано!');
    }

    // Просмотр конкретного обращения
    public function show(SupportTicket $ticket)
    {
        // Проверка: пользователь может видеть только свои тикеты
        if ($ticket->user_id !== auth()->id() && !auth()->user()->isModerator()) {
            abort(403);
        }

        $ticket->load(['user', 'assignedUser']);

        return view('support.show', compact('ticket'));
    }

    // Ответ персонала на обращение (только для модераторов/админов)
    public function reply(Request $request, SupportTicket $ticket)
    {
        if (!auth()->user()->isModerator()) {
            abort(403);
        }

        $validated = $request->validate([
            'reply' => 'required|string|min:5',
        ]);

        // Добавляем ответ к сообщению тикета
        $ticket->message .= "\n\n--- Ответ от " . auth()->user()->name . " (" . now()->format('d.m.Y H:i') . ") ---\n" . $validated['reply'];
        $ticket->save();

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Ответ отправлен!');
    }
}