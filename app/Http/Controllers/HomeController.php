<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\MenuItem;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::where('is_active', true);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by city
        if ($city = $request->get('city')) {
            $query->where('city', $city);
        }

        // Sort
        $sort = $request->get('sort', 'rating');
        match($sort) {
            'rating'       => $query->orderByDesc('rating'),
            'delivery_fee' => $query->orderBy('delivery_fee'),
            'delivery_time'=> $query->orderBy('delivery_time'),
            'newest'       => $query->latest(),
            default        => $query->orderByDesc('rating'),
        };

        $restaurants = $query->paginate(9);
        $categories  = Category::where('is_active', true)->get();
        $cities      = Restaurant::where('is_active', true)->distinct()->pluck('city');
        $featured    = Restaurant::where('is_active', true)->where('rating', '>=', 4.5)->take(3)->get();

        return view('home.index', compact('restaurants', 'categories', 'cities', 'featured', 'sort'));
    }
}
