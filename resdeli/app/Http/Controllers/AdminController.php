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

        $stats = [
            'users'       => User::count(),
            'restaurants' => Restaurant::count(),
            'orders'      => Order::count(),
            'revenue'     => Order::where('status', 'delivered')->sum('total'),
            'pending'     => Order::where('status', 'pending')->count(),
            'reviews'     => Review::where('is_approved', false)->count(),
        ];

        $recentOrders = Order::with(['user', 'restaurant'])->latest()->take(8)->get();
        $topRestaurants = Restaurant::orderByDesc('rating')->take(5)->get();
        $monthlySales = Order::where('status', 'delivered')
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Order status counts for chart
        $orderStatuses = collect(['pending', 'confirmed', 'preparing', 'delivering', 'delivered', 'cancelled'])
            ->map(function($status) {
                return [
                    'status' => $status,
                    'count' => Order::where('status', $status)->count()
                ];
            });

        // Top restaurants by order count
        $topRestaurantsByOrders = Restaurant::withCount('orders')
            ->orderByDesc('orders_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topRestaurants', 'monthlySales', 'orderStatuses', 'topRestaurantsByOrders'));
    }

    public function dashboardData()
    {
        $this->ensureAdmin();

        $stats = [
            'users'       => User::count(),
            'restaurants' => Restaurant::count(),
            'orders'      => Order::count(),
            'revenue'     => Order::where('status', 'delivered')->sum('total'),
            'pending'     => Order::where('status', 'pending')->count(),
            'reviews'     => Review::where('is_approved', false)->count(),
        ];

        $monthlySales = Order::where('status', 'delivered')
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $orderStatuses = collect(['pending', 'confirmed', 'preparing', 'delivering', 'delivered', 'cancelled'])
            ->map(function($status) {
                return [
                    'status' => $status,
                    'count' => Order::where('status', $status)->count(),
                ];
            });

        $topRestaurantsByOrders = Restaurant::withCount('orders')
            ->orderByDesc('orders_count')
            ->take(5)
            ->get(['id', 'name', 'orders_count']);

        return response()->json([
            'stats' => $stats,
            'monthly_sales' => $monthlySales,
            'order_statuses' => $orderStatuses,
            'top_restaurants_by_orders' => $topRestaurantsByOrders,
        ]);
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
