<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Tag;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin-action');

        $query = MenuItem::with(['restaurant', 'category']);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($restaurant_id = $request->get('restaurant_id')) {
            $query->where('restaurant_id', $restaurant_id);
        }
        if ($category_id = $request->get('category_id')) {
            $query->where('category_id', $category_id);
        }

        $sort = $request->get('sort', 'newest');
        match($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name'       => $query->orderBy('name'),
            default      => $query->latest(),
        };

        $items       = $query->paginate(12);
        $restaurants = Restaurant::where('is_active', true)->get();
        $categories  = Category::where('is_active', true)->get();

        return view('admin.menu-items.index', compact('items', 'restaurants', 'categories', 'sort'));
    }

    public function create()
    {
        $this->authorize('admin-action');
        $restaurants = Restaurant::where('is_active', true)->get();
        $categories  = Category::where('is_active', true)->get();
        $tags        = Tag::all();
        return view('admin.menu-items.create', compact('restaurants', 'categories', 'tags'));
    }

    public function store(Request $request)
    {
        $this->authorize('admin-action');

        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|min:3|max:100',
            'description'   => 'nullable|string|max:500',
            'price'         => 'required|numeric|min:1000',
            'sale_price'    => 'nullable|numeric|lt:price',
            'prep_time'     => 'required|integer|min:1|max:120',
            'calories'      => 'nullable|integer|min:0',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tags'          => 'nullable|array',
            'tags.*'        => 'exists:tags,id',
        ], [
            'price.min'          => 'Giá phải từ 1,000đ trở lên.',
            'sale_price.lt'      => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
            'restaurant_id.exists' => 'Nhà hàng không tồn tại.',
            'category_id.exists'   => 'Danh mục không tồn tại.',
            'image.mimes'        => 'Ảnh phải là jpeg, png, jpg hoặc webp.',
            'image.max'          => 'Ảnh không được vượt quá 2MB.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        $validated['slug']         = Str::slug($request->name) . '-' . time();
        $validated['is_available'] = $request->boolean('is_available', true);
        $validated['is_featured']  = $request->boolean('is_featured');

        $item = MenuItem::create($validated);

        if ($request->has('tags')) {
            $item->tags()->sync($request->tags);
        }

        return redirect()->route('admin.menu-items.index')->with('success', 'Món ăn đã được thêm thành công!');
    }

    public function edit(MenuItem $menuItem)
    {
        $this->authorize('admin-action');
        $restaurants = Restaurant::where('is_active', true)->get();
        $categories  = Category::where('is_active', true)->get();
        $tags        = Tag::all();
        return view('admin.menu-items.edit', compact('menuItem', 'restaurants', 'categories', 'tags'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $this->authorize('admin-action');

        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|min:3|max:100',
            'description'   => 'nullable|string|max:500',
            'price'         => 'required|numeric|min:1000',
            'sale_price'    => 'nullable|numeric|lt:price',
            'prep_time'     => 'required|integer|min:1|max:120',
            'calories'      => 'nullable|integer|min:0',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tags'          => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            if ($menuItem->image) Storage::disk('public')->delete($menuItem->image);
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available');
        $validated['is_featured']  = $request->boolean('is_featured');

        $menuItem->update($validated);
        $menuItem->tags()->sync($request->tags ?? []);

        return redirect()->route('admin.menu-items.index')->with('success', 'Cập nhật món ăn thành công!');
    }

    public function destroy(MenuItem $menuItem)
    {
        $this->authorize('admin-action');
        if ($menuItem->image) Storage::disk('public')->delete($menuItem->image);
        $menuItem->delete();
        return redirect()->route('admin.menu-items.index')->with('success', 'Đã xóa món ăn!');
    }
}
