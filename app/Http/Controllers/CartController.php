<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('product.user')->get();
        $total = auth()->user()->cart_total;

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (!$product->in_stock) {
            return back()->with('error', 'Товар отсутствует в наличии.');
        }

        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $validated['quantity'] ?? 1;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'] ?? 1,
            ]);
        }

        return back()->with('success', 'Товар добавлен в корзину!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('success', 'Количество обновлено.');
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Товар удалён из корзины.');
    }

    public function checkout()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        $total = auth()->user()->cart_total;

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста.');
        }

        return view('cart.checkout', compact('cartItems', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string|min:10',
            'comment' => 'nullable|string|max:1000',
        ]);

        $cartItems = auth()->user()->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Корзина пуста.');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->subtotal;
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'delivery_address' => $validated['delivery_address'],
            'comment' => $validated['comment'] ?? null,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Очищаем корзину
        auth()->user()->cartItems()->delete();

        return redirect()->route('orders.show', $order)
            ->with('success', 'Заказ оформлен! Номер: ' . $order->order_number);
    }
}