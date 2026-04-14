<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\MenuItem;

class RestaurantController extends Controller
{
    // Public: show restaurant detail
    public function show(Restaurant $restaurant)
    {
        if (!$restaurant->is_active) abort(404);

        // Lấy tất cả menu items (bao gồm unavailable để hiển thị)
        $menuItems = $restaurant->menuItems()
            ->with(['category', 'tags'])
            ->get()
            ->groupBy('category.name');

        $categories = $restaurant->menuItems()
            ->with('category')
            ->get()
            ->pluck('category')
            ->unique('id');

        $reviews = $restaurant->reviews()
            ->where('is_approved', true)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $isFavorited = Auth::check()
            ? Auth::user()->favorites()->where('restaurant_id', $restaurant->id)->exists()
            : false;

        return view('restaurants.show', compact('restaurant', 'menuItems', 'categories', 'reviews', 'isFavorited'));
    }

    // Admin: list all restaurants
    public function index(Request $request)
    {
        $this->authorize('admin-action');

        $query = Restaurant::with('owner');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
        }

        $restaurants = $query->latest()->paginate(10);
        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        $this->authorize('admin-action');
        return view('admin.restaurants.create');
    }

    public function store(Request $request)
    {
        $this->authorize('admin-action');

        $validated = $request->validate([
            'name'          => 'required|string|min:3|max:100|unique:restaurants,name',
            'description'   => 'nullable|string|max:500',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'phone'         => 'required|regex:/^[0-9]{10,11}$/',
            'email'         => 'nullable|email',
            'delivery_time' => 'required|integer|min:5|max:120',
            'delivery_fee'  => 'required|numeric|min:0',
            'min_order'     => 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'open_time'     => 'required',
            'close_time'    => 'required',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('restaurants', 'public');
        }

        $validated['slug']     = Str::slug($request->name) . '-' . time();
        $validated['owner_id'] = Auth::id();

        Restaurant::create($validated);

        return redirect()->route('admin.restaurants.index')->with('success', 'Nhà hàng đã được tạo thành công!');
    }

    public function edit(Restaurant $restaurant)
    {
        $this->authorize('admin-action');
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $this->authorize('admin-action');

        $validated = $request->validate([
            'name'          => 'required|string|min:3|max:100|unique:restaurants,name,' . $restaurant->id,
            'description'   => 'nullable|string|max:500',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'phone'         => 'required|regex:/^[0-9]{10,11}$/',
            'email'         => 'nullable|email',
            'delivery_time' => 'required|integer|min:5|max:120',
            'delivery_fee'  => 'required|numeric|min:0',
            'min_order'     => 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_open'       => 'boolean',
            'is_active'     => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($restaurant->image) Storage::disk('public')->delete($restaurant->image);
            $validated['image'] = $request->file('image')->store('restaurants', 'public');
        }

        $validated['is_open']   = $request->boolean('is_open');
        $validated['is_active'] = $request->boolean('is_active');

        $restaurant->update($validated);

        return redirect()->route('admin.restaurants.index')->with('success', 'Cập nhật nhà hàng thành công!');
    }

    public function destroy(Restaurant $restaurant)
    {
        $this->authorize('admin-action');
        if ($restaurant->image) Storage::disk('public')->delete($restaurant->image);
        $restaurant->delete();
        return redirect()->route('admin.restaurants.index')->with('success', 'Đã xóa nhà hàng!');
    }

    // Toggle favorite
    public function toggleFavorite(Request $request, Restaurant $restaurant)
    {
        if (!Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);

        $user    = Auth::user();
        $isFaved = $user->favorites()->where('restaurant_id', $restaurant->id)->exists();

        if ($isFaved) {
            $user->favorites()->detach($restaurant->id);
            $status = false;
        } else {
            $user->favorites()->attach($restaurant->id);
            $status = true;
        }

        return response()->json(['favorited' => $status]);
    }
}
