<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        $order->load('user', 'items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function destroy(Request $request, Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('status', 'Order deleted successfully.');
    }
}
