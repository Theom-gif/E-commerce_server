<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalRevenue = Order::sum('total') ?? 0;
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Recent Orders
        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Top Products (by sales)
        $topProducts = Product::withCount(['orderItems'])
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        // Monthly Revenue
        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total) as amount')
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->pluck('amount', 'month')
            ->toArray();

        // User Growth (last 7 days)
        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Recent Reviews
        $recentReviews = Review::with(['user', 'product'])
            ->latest()
            ->take(5)
            ->get();

        // Order Status Distribution
        $orderStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Top Categories
        $topCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOrders',
            'totalProducts',
            'totalCategories',
            'totalRevenue',
            'averageOrderValue',
            'recentOrders',
            'topProducts',
            'monthlyRevenue',
            'userGrowth',
            'recentReviews',
            'orderStatus',
            'topCategories'
        ));
    }
}
