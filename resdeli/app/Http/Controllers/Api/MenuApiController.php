<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\MenuItem;
use App\Models\Category;

class MenuApiController extends Controller
{
    // GET /api/restaurants
    public function restaurants(Request $request)
    {
        $query = Restaurant::where('is_active', true);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
        }
        if ($city = $request->get('city')) {
            $query->where('city', $city);
        }

        $sort = $request->get('sort', 'rating');
        match($sort) {
            'rating'        => $query->orderByDesc('rating'),
            'delivery_fee'  => $query->orderBy('delivery_fee'),
            'delivery_time' => $query->orderBy('delivery_time'),
            default         => $query->orderByDesc('rating'),
        };

        $restaurants = $query->paginate(9);

        return response()->json([
            'data'  => $restaurants->items(),
            'meta'  => [
                'total'        => $restaurants->total(),
                'current_page' => $restaurants->currentPage(),
                'last_page'    => $restaurants->lastPage(),
            ],
        ]);
    }

    // GET /api/restaurants/{id}/menu
    public function restaurantMenu(Restaurant $restaurant)
    {
        if (!$restaurant->is_active) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        }

        $menu = $restaurant->menuItems()
            ->where('is_available', true)
            ->with(['category', 'tags'])
            ->get()
            ->map(function ($item) {
                return [
                    'id'             => $item->id,
                    'name'           => $item->name,
                    'slug'           => $item->slug,
                    'description'    => $item->description,
                    'price'          => $item->price,
                    'sale_price'     => $item->sale_price,
                    'effective_price'=> $item->effective_price,
                    'image_url'      => $item->image_url,
                    'category'       => $item->category?->name,
                    'tags'           => $item->tags->pluck('name'),
                    'prep_time'      => $item->prep_time,
                    'calories'       => $item->calories,
                    'is_featured'    => $item->is_featured,
                ];
            })
            ->groupBy('category');

        return response()->json([
            'restaurant' => [
                'id'           => $restaurant->id,
                'name'         => $restaurant->name,
                'rating'       => $restaurant->rating,
                'delivery_fee' => $restaurant->delivery_fee,
                'min_order'    => $restaurant->min_order,
                'delivery_time'=> $restaurant->delivery_time,
            ],
            'menu' => $menu,
        ]);
    }

    // GET /api/categories
    public function categories()
    {
        $categories = Category::where('is_active', true)->get(['id', 'name', 'slug', 'icon']);
        return response()->json(['data' => $categories]);
    }

    // GET /api/search?q=...
    public function search(Request $request)
    {
        $q = $request->get('q');

        if (!$q || strlen($q) < 2) {
            return response()->json(['restaurants' => [], 'items' => []]);
        }

        $restaurants = Restaurant::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('city', 'like', "%{$q}%");
            })
            ->take(5)
            ->get(['id', 'name', 'slug', 'city', 'rating', 'image']);

        $items = MenuItem::where('is_available', true)
            ->where('name', 'like', "%{$q}%")
            ->with('restaurant')
            ->take(5)
            ->get();

        return response()->json([
            'restaurants' => $restaurants->map(fn($r) => [
                'id'       => $r->id,
                'name'     => $r->name,
                'slug'     => $r->slug,
                'city'     => $r->city,
                'rating'   => $r->rating,
                'image_url'=> $r->image_url,
            ]),
            'items' => $items->map(fn($i) => [
                'id'              => $i->id,
                'name'            => $i->name,
                'restaurant_name' => $i->restaurant->name,
                'restaurant_slug' => $i->restaurant->slug,
                'effective_price' => $i->effective_price,
                'image_url'       => $i->image_url,
            ]),
        ]);
    }
}
