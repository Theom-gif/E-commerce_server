<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $selectedStatus = $request->query('status');

        $query = Order::with('user')->withCount('items');

        if ($selectedStatus) {
            $query->where('status', $selectedStatus);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        // Cache heavy aggregate stats for 5 minutes
        $stats = Cache::remember('admin.orders.stats', 300, function () {
            $totalOrders = Order::count();
            $totalRevenue = Order::sum('total');

            return [
                'totalOrders' => $totalOrders,
                'totalRevenue' => $totalRevenue,
                'averageOrderValue' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
                'statusCounts' => Order::selectRaw('status, COUNT(*) as aggregate')
                    ->groupBy('status')
                    ->pluck('aggregate', 'status')
                    ->toArray(),
            ];
        });

        $recentOrders = Order::with('user')
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.orders.index', array_merge(
            $stats,
            compact('orders', 'selectedStatus', 'recentOrders')
        ));
    }

    public function show(Request $request, Order $order)
    {
        $order->load('user', 'items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function complete(Request $request, Order $order)
    {
        $order->update(['status' => 'completed']);

        // Bust the stats cache since order status changed
        Cache::forget('admin.orders.stats');
        Cache::forget('admin.dashboard.stats');

        return redirect()->back()->with('success', 'Order marked as completed.');
    }

    public function destroy(Request $request, Order $order)
    {
        $order->delete();

        // Bust the stats cache since an order was removed
        Cache::forget('admin.orders.stats');
        Cache::forget('admin.dashboard.stats');

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
