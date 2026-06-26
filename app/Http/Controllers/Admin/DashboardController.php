<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin.dashboard.stats', 300, function () {
            $totalOrders = Order::count();
            $totalRevenue = Order::sum('total') ?? 0;

            return [
                'totalUsers' => User::where('role', 'user')->count(),
                'totalOrders' => $totalOrders,
                'totalProducts' => Product::count(),
                'totalCategories' => Category::count(),
                'totalRevenue' => $totalRevenue,
                'averageOrderValue' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
                'monthlyRevenue' => Order::selectRaw('MONTH(created_at) as month, SUM(total) as amount')
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(DB::raw('MONTH(created_at)'))
                    ->orderBy('month')
                    ->pluck('amount', 'month')
                    ->toArray(),
                'orderStatus' => Order::selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status')
                    ->toArray(),
            ];
        });

        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', array_merge($stats, compact('recentOrders', 'topProducts')));
    }
}
