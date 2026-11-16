<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::active()
            ->acceptingOrders()
            ->with('user');

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Deadline filter
        if ($request->filled('deadline')) {
            $query->where('deadline', 'like', "%{$request->deadline}%");
        }

        // Sort
        $sort = $request->get('sort', 'rating');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price_from', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_from', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('rating', 'desc');
        }

        $services = $query->paginate(9)->withQueryString();
        $mastersCount = User::where('role', User::ROLE_MASTER)
            ->where('is_active', true)
            ->count();

        return view('masters.index', compact('services', 'mastersCount'));
    }

    public function show(Service $service)
    {
        $service->load('user');
        $portfolio = $service->user->products()->active()->limit(6)->get();

        return view('masters.show', compact('service', 'portfolio'));
    }

    public function create()
    {
        if (!auth()->user()->isMaster()) {
            abort(403, 'Только мастера могут предлагать услуги.');
        }

        return view('masters.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isMaster()) {
            abort(403, 'Только мастера могут предлагать услуги.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:art,costume,3d,music,design,craft',
            'price_from' => 'required|numeric|min:0',
            'deadline' => 'required|string|max:100',
        ]);

        $validated['user_id'] = auth()->id();
        
        // ИСПРАВЛЕНО: статус pending вместо accepts_orders = true
        $validated['status'] = 'pending';
        $validated['accepts_orders'] = false;

        $service = Service::create($validated);

        \App\Models\ActivityLog::log('service_created', $service);

        return redirect()->route('masters.index')
            ->with('success', 'Услуга отправлена на модерацию!');
    }
}