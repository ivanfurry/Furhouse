<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->inStock()->with(['user', 'category']);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Price filter
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $query->priceRange($request->price_min, $request->price_max);
        }

        // In stock filter
        if ($request->filled('in_stock')) {
            $query->inStock();
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        // Delivery filter
        if ($request->filled('delivery')) {
            $query->where('delivery_type', $request->delivery);
        }

        // Sort
        $sort = $request->get('sort', 'popular');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('rating', 'desc')->orderBy('views_count', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->ordered()->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->increment('views_count');
        $product->load(['user', 'category', 'reviews.user']);

        $relatedProducts = Product::active()
            ->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function create()
    {
        $this->authorizeMaster();
        $categories = Category::active()->ordered()->get();
        return view('products.create', compact('categories'));
    }

     public function store(Request $request)
    {
        if (!auth()->user()->can_post_products) {
        return back()->with('error', 'Вам запрещено размещать товары.');
        }

        $this->authorizeMaster();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'in_stock' => 'boolean',
            'delivery_type' => 'nullable|in:russia,pickup,digital',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);
        
        // ИСПРАВЛЕНО: статус draft вместо active — требует модерации
        $validated['status'] = 'draft';

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        // Логирование
        \App\Models\ActivityLog::log('product_created', $product);

        return redirect()->route('products.index')
            ->with('success', 'Товар отправлен на модерацию!');
    }

    public function edit(Product $product)
    {
        if ($product->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $categories = Category::active()->ordered()->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'in_stock' => 'boolean',
            'delivery_type' => 'nullable|in:russia,pickup,digital',
            'status' => 'in:draft,active,sold,archived',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Товар обновлён!');
    }

    public function destroy(Product $product)
    {
        if ($product->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Товар удалён.');
    }

    public function toggleFavorite(Product $product)
    {
        $user = auth()->user();
        $existing = $product->favorites()->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            $product->decrement('favorites_count');
            return response()->json(['favorited' => false]);
        } else {
            $product->favorites()->create(['user_id' => $user->id]);
            $product->increment('favorites_count');
            return response()->json(['favorited' => true]);
        }
    }

    private function authorizeMaster(): void
    {
        if (!auth()->user()->isMaster()) {
            abort(403, 'Только мастера могут размещать товары.');
        }
    }
}