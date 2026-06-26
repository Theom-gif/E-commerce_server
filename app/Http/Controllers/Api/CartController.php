<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->cart()->with('product.category')->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $cart = $request->user()->cart()->firstOrNew(['product_id' => $validated['product_id']]);
        $cart->quantity = ($cart->exists ? (int) $cart->quantity : 0) + ($validated['quantity'] ?? 1);
        $cart->save();

        return response()->json($cart->load('product.category'));
    }

    public function update(Request $request, Cart $cart)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        abort_unless($cart->user_id === $request->user()->id, 403);

        $cart->update(['quantity' => $validated['quantity']]);

        return response()->json($cart->load('product.category'));
    }

    public function destroy(Request $request, Cart $cart)
    {
        abort_unless($cart->user_id === $request->user()->id, 403);

        $cart->delete();

        return response()->json(['message' => 'Removed from cart']);
    }
}
