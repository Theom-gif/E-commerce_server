<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $request->user()->orders()->latest()->get();
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return $request->user()->orders()->with('items.product')->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
    public function checkout(Request $request)
    {
        $user = $request->user();
        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        DB::transaction(function () use ($user, $cartItems, &$order) {
            $total = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

            $order = $user->orders()->create([
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                $itemTotal = $item->product->price * $item->quantity;

                $order->items()->create([
                    'user_id' => $user->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'total' => $itemTotal,
                ]);
            }

            $user->cart()->delete();
        });

        return response()->json(['message' => 'Order placed', 'order' => $order->load('items')]);
    }
}
