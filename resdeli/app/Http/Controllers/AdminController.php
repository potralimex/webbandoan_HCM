<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Review;

class AdminController extends Controller
{
    // ===== VIEW DASHBOARD =====
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_restaurants' => Restaurant::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'pending_reviews' => Review::where('is_approved', 0)->count(),
        ];

        $recentOrdersFormatted = Order::with(['user', 'restaurant'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(function ($o) {
                return [
                    'order_number' => $o->order_number,
                    'customer_name' => optional($o->user)->name ?? 'Khách',
                    'restaurant_name' => optional($o->restaurant)->name ?? '',
                    'status_badge' => $o->status_badge,
                    'total' => $o->total,
                ];
            })->values();

        $monthlySales = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $orderStatuses = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $topRestaurantsByOrders = Restaurant::withCount('orders')
            ->orderByDesc('orders_count')
            ->limit(5)
            ->get()
            ->map(function ($r) {
                return [
                    'name' => $r->name,
                    'orders_count' => $r->orders_count,
                ];
            });

        return view('admin.dashboard', compact(
            'stats',
            'recentOrdersFormatted',
            'monthlySales',
            'orderStatuses',
            'topRestaurantsByOrders'
        ));
    }

    // ===== API DASHBOARD =====
    public function dashboardApi()
    {
        return response()->json([
            'stats' => [
                'total_users' => User::count(),
                'total_restaurants' => Restaurant::count(),
                'total_orders' => Order::count(),
            ],
            'recent_orders' => Order::with(['user','restaurant'])
                ->latest()
                ->limit(8)
                ->get()
                ->map(function ($o) {
                    return [
                        'order_number' => $o->order_number,
                        'customer_name' => optional($o->user)->name ?? 'Khách',
                        'restaurant_name' => optional($o->restaurant)->name ?? '',
                        'status_badge' => $o->status_badge,
                        'total' => $o->total,
                    ];
                })->values()
        ]);
    }
}