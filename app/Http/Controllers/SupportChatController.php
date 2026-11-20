<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;

class SupportChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Список обращений для мастера/персонала
    public function indexForStaff()
    {
        $user = auth()->user();
        
        // Получаем обращения, назначенные на пользователя или все (для админов)
        $query = SupportTicket::with(['user', 'messages.user']);
        
        if (!$user->isAdmin()) {
            $query->where('assigned_to', $user->id);
        }
        
        $tickets = $query->orderBy('updated_at', 'desc')->paginate(15);
        
        return view('support.chat.index', compact('tickets'));
    }

    // Список обращений для пользователя
    public function indexForUser()
    {
        $tickets = SupportTicket::where('user_id', auth()->id())
            ->with(['assignedUser', 'messages.user'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
        
        return view('support.chat.index', compact('tickets'));
    }

    // Просмотр чата
    public function show(SupportTicket $ticket)
    {
        // Автор обращения, модератор или назначенный мастер — все могут видеть
        if (!$ticket->canUserView(auth()->user())) {
            abort(403, 'У вас нет доступа к этому обращению.');
        }

        $ticket->load(['user', 'assignedUser', 'service', 'messages.user']);

        // Автоматически берём в работу, если автор тикета не открыл его
        if ($ticket->status === 'open' && $ticket->user_id !== auth()->id() && auth()->user()->isModerator()) {
            $ticket->update(['status' => 'in_progress']);
        }

        return view('support.chat.show', compact('ticket'));
    }

    // Отправка сообщения
    public function sendMessage(Request $request, SupportTicket $ticket)
    {
        if (!$ticket->canUserView(auth()->user())) {
            abort(403);
        }
        
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:5000',
        ]);
        
        $isStaff = auth()->user()->isModerator();
        
        $message = SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_staff' => $isStaff,
        ]);
        
        // Обновляем статус
        if ($ticket->status === 'open' && !$isStaff) {
            $ticket->update(['status' => 'in_progress']);
        } elseif ($ticket->status === 'resolved' && !$isStaff) {
            $ticket->update(['status' => 'in_progress']);
        }
        
        $ticket->touch(); // Обновляем updated_at
        
        return back()->with('success', 'Сообщение отправлено.');
    }

    // Закрытие обращения (только для персонала)
    public function closeTicket(SupportTicket $ticket)
    {
        if (!auth()->user()->isModerator()) {
            abort(403);
        }
        
        $ticket->update(['status' => 'resolved']);
        
        return back()->with('success', 'Обращение закрыто.');
    }

    // Назначить обращение себе
    public function assignToMe(SupportTicket $ticket)
    {
        if (!auth()->user()->isModerator()) {
            abort(403);
        }
        
        $ticket->update([
            'assigned_to' => auth()->id(),
            'status' => 'in_progress',
        ]);
        
        return back()->with('success', 'Обращение назначено вам.');
    }
}