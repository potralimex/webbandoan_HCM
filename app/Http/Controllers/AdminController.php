<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\MenuItem;
use App\Models\Review;
use App\Models\Category;

class AdminController extends Controller
{
    private function ensureAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }
    }

    public function dashboard()
    {
        $this->ensureAdmin();

        $now = now();
        $thisMonth = $now->month;
        $thisYear  = $now->year;
        $lastMonth = $now->copy()->subMonth()->month;
        $lastYear  = $now->copy()->subMonth()->year;

        // KPI current month
        $revenueThisMonth = Order::where('status', 'delivered')
            ->whereYear('created_at', $thisYear)->whereMonth('created_at', $thisMonth)->sum('total');
        $revenueLastMonth = Order::where('status', 'delivered')
            ->whereYear('created_at', $lastYear)->whereMonth('created_at', $lastMonth)->sum('total');

        $ordersThisMonth = Order::whereYear('created_at', $thisYear)->whereMonth('created_at', $thisMonth)->count();
        $ordersLastMonth = Order::whereYear('created_at', $lastYear)->whereMonth('created_at', $lastMonth)->count();

        $usersThisMonth = User::whereYear('created_at', $thisYear)->whereMonth('created_at', $thisMonth)->count();
        $usersLastMonth = User::whereYear('created_at', $lastYear)->whereMonth('created_at', $lastMonth)->count();

        $cancelledThisMonth = Order::where('status', 'cancelled')
            ->whereYear('created_at', $thisYear)->whereMonth('created_at', $thisMonth)->count();
        $cancelledLastMonth = Order::where('status', 'cancelled')
            ->whereYear('created_at', $lastYear)->whereMonth('created_at', $lastMonth)->count();

        $stats = [
            'users'              => User::count(),
            'restaurants'        => Restaurant::count(),
            'orders'             => Order::count(),
            'revenue'            => Order::where('status', 'delivered')->sum('total'),
            'pending'            => Order::where('status', 'pending')->count(),
            'reviews'            => Review::where('is_approved', false)->count(),
            'revenueThisMonth'   => $revenueThisMonth,
            'revenueLastMonth'   => $revenueLastMonth,
            'ordersThisMonth'    => $ordersThisMonth,
            'ordersLastMonth'    => $ordersLastMonth,
            'usersThisMonth'     => $usersThisMonth,
            'usersLastMonth'     => $usersLastMonth,
            'cancelledThisMonth' => $cancelledThisMonth,
            'cancelledLastMonth' => $cancelledLastMonth,
        ];

        $recentOrders = Order::with(['user', 'restaurant'])->latest()->take(8)->get();

        // Top restaurants by revenue
        $topRestaurants = Restaurant::withCount('orders')
            ->withSum(['orders' => fn($q) => $q->where('status', 'delivered')], 'total')
            ->orderByDesc('orders_sum_total')
            ->take(5)
            ->get();

        // Monthly revenue last 12 months
        $monthlySales = Order::where('status', 'delivered')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total) as total, COUNT(*) as count')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->take(-12);

        // Order status breakdown
        $orderStatusData = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        // Payment method breakdown
        $paymentData = Order::selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')->pluck('count', 'payment_method');

        // Daily orders last 30 days
        $dailyOrders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as revenue')
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('date')->orderBy('date')->get();

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'topRestaurants',
            'monthlySales', 'orderStatusData', 'paymentData', 'dailyOrders'
        ));
    }

    // Users management
    public function users(Request $request)
    {
        $this->ensureAdmin();
        $query = User::with('profile');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }
        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        $this->ensureAdmin();
        if ($user->isAdmin()) {
            return back()->withErrors(['user' => 'Không thể khóa tài khoản admin.']);
        }
        $user->update([
            'email_verified_at' => $user->email_verified_at ? null : now(),
        ]);
        return back()->with('success', 'Đã cập nhật trạng thái người dùng!');
    }

    // Categories management
    public function categories(Request $request)
    {
        $this->ensureAdmin();
        $categories = Category::withCount('menuItems')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        $this->ensureAdmin();
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'name'        => 'required|string|min:2|max:50|unique:categories,name',
            'icon'        => 'nullable|string|max:10',
            'description' => 'nullable|string|max:200',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        Category::create($validated);

        return redirect()->route('admin.categories')->with('success', 'Đã thêm danh mục!');
    }

    public function editCategory(Category $category)
    {
        $this->ensureAdmin();
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'name'        => 'required|string|min:2|max:50|unique:categories,name,' . $category->id,
            'icon'        => 'nullable|string|max:10',
            'description' => 'nullable|string|max:200',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $category->update($validated);

        return redirect()->route('admin.categories')->with('success', 'Đã cập nhật danh mục!');
    }

    public function destroyCategory(Category $category)
    {
        $this->ensureAdmin();
        $category->delete();
        return back()->with('success', 'Đã xóa danh mục!');
    }
}
