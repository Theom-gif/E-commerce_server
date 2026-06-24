<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $request->user()->cart()->with('product')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required', 'quantity' => 'required|integer|min:1']);
        $cart = $request->user()->cart()->firstOrNew(['product_id' => $request->product_id]);
        $cart->quantity = ($cart->quantity ?? 0) + $request->quantity;
        $cart->save();

    return response()->json($cart);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
            $cart = $request->user()->cart()->findOrFail($id);
            $cart->update(['quantity' => $request->quantity]);
            return response()->json($cart);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $request->user()->cart()->where('id', $id)->delete();
        return response()->json(['message' => 'Removed from cart']);
    }
}
