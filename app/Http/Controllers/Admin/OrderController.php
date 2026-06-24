<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $selectedStatus = $request->query('status');

        $query = Order::with(['user'])->withCount('items');

        if ($selectedStatus) {
            $query->where('status', $selectedStatus);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        $statsQuery = Order::query();
        $totalOrders = (clone $statsQuery)->count();
        $totalRevenue = (clone $statsQuery)->sum('total');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $statusCounts = (clone $statsQuery)
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->toArray();
        $recentOrders = Order::with(['user'])->withCount('items')->latest()->take(5)->get();

        return view('admin.orders.index', compact(
            'orders',
            'selectedStatus',
            'totalOrders',
            'totalRevenue',
            'averageOrderValue',
            'statusCounts',
            'recentOrders'
        ));
    }

    public function overview()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalRevenue = Order::sum('total') ?? 0;
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total) as amount')
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->pluck('amount', 'month')
            ->toArray();

        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $topProducts = Product::withCount(['orderItems'])
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        $topCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(5)
            ->get();

        return view(
            'admin.orders.index',
            compact(
                'totalUsers',
                'totalOrders',
                'totalProducts',
                'totalCategories',
                'totalRevenue',
                'averageOrderValue',
                'recentOrders',
                'monthlyRevenue',
                'userGrowth',
                'topProducts',
                'topCategories'
            )
        );
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
