<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\User;

class TeamController extends Controller
{
    public function index()
    {
        $teamMembers = User::whereIn('role', ['admin', 'moderator'])
            ->where('is_active', true)
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return view('team', compact('teamMembers'));
    }
}