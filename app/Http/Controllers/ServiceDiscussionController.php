<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;

class ServiceDiscussionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Создать заявку на обсуждение услуги
    public function create(Service $service)
    {
        // Пользователь не может обсуждать свою услугу
        if ($service->user_id === auth()->id()) {
            return back()->with('error', 'Вы не можете обсуждать свою услугу.');
        }

        return view('service-discussion.create', compact('service'));
    }

    // Отправить заявку
    public function store(Request $request, Service $service)
    {
        if ($service->user_id === auth()->id()) {
            return back()->with('error', 'Вы не можете обсуждать свою услугу.');
        }

        $validated = $request->validate([
            'message' => 'required|string|min:10|max:1000',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'assigned_to' => $service->user_id,
            'service_id' => $service->id,
            'type' => 'service_discussion',
            'subject' => 'Обсуждение услуги: ' . $service->title,
            'message' => $validated['message'],
            'status' => 'open',
            'priority' => 'medium',
        ]);

        // Создаём первое сообщение
        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_staff' => false,
        ]);

        return redirect()->route('service-discussion.show', $ticket)
            ->with('success', 'Заявка отправлена мастеру!');
    }

    // Список заявок для мастера
    public function indexForMaster()
    {
        $user = auth()->user();
        
        // Получаем услуги мастера
        $serviceIds = Service::where('user_id', $user->id)->pluck('id');
        
        // Получаем тикеты по этим услугам
        $tickets = SupportTicket::where('type', 'service_discussion')
            ->whereIn('service_id', $serviceIds)
            ->with(['user', 'service', 'messages'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('service-discussion.master-index', compact('tickets'));
    }

    // Просмотр чата (для пользователя или мастера)
    public function show(SupportTicket $ticket)
    {
        if (!$ticket->canUserView(auth()->user())) {
            abort(403, 'У вас нет доступа к этому обсуждению.');
        }

        $ticket->load(['user', 'assignedUser', 'service', 'messages.user']);

        return view('service-discussion.show', compact('ticket'));
    }

    // Отправить сообщение в чат
    public function sendMessage(Request $request, SupportTicket $ticket)
    {
        if (!$ticket->canUserView(auth()->user())) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|min:1|max:5000',
        ]);

        $isMaster = $ticket->service && $ticket->service->user_id === auth()->id();

        SupportMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_staff' => $isMaster,
        ]);

        // Обновляем статус
        if ($ticket->status === 'open' && $isMaster) {
            $ticket->update(['status' => 'in_progress']);
        }

        $ticket->touch();

        return back()->with('success', 'Сообщение отправлено.');
    }

    // Закрыть обсуждение (только мастер)
    public function close(SupportTicket $ticket)
    {
        if (!$ticket->service || $ticket->service->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->update(['status' => 'resolved']);

        return back()->with('success', 'Обсуждение закрыто.');
    }
}