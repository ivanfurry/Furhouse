<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->inStock()
            ->with(['user', 'category'])
            ->orderBy('rating', 'desc')
            ->limit(3)
            ->get();

        $categories = Category::active()->ordered()->get();

        $upcomingEvents = Event::upcoming()
            ->with('user')
            ->limit(3)
            ->get();

        $featuredEvent = Event::featured()
            ->upcoming()
            ->with('user')
            ->first();

        return view('home', compact(
            'featuredProducts',
            'categories',
            'upcomingEvents',
            'featuredEvent'
        ));
    }
}