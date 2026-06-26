<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()
                ->orders()
                ->with('items.product')
                ->latest()
                ->get()
        );
    }

    public function show(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return response()->json($order->load('items.product'));
    }

    public function checkout(Request $request)
    {
        $user = $request->user();
        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $order = null;

        DB::transaction(function () use ($user, $cartItems, &$order) {
            $total = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

            $order = $user->orders()->create([
                'total' => $total,
                'status' => 'pending',
            ]);

            $orderItems = $cartItems->map(fn ($item) => [
                'user_id' => $user->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'total' => $item->product->price * $item->quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            $order->items()->insert($orderItems);
            $user->cart()->delete();
        });

        // Bust admin caches
        Cache::forget('admin.orders.stats');
        Cache::forget('admin.dashboard.stats');

        return response()->json([
            'message' => 'Order placed',
            'order' => $order->load('items.product'),
        ]);
    }

    public function complete(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->update(['status' => 'completed']);

        Cache::forget('admin.orders.stats');
        Cache::forget('admin.dashboard.stats');

        return response()->json([
            'message' => 'Order marked as completed',
            'order' => $order,
        ]);
    }
}
