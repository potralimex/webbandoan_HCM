<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\MenuApiController;

// === PUBLIC ROUTES ===
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Restaurants (public)
Route::get('/restaurants/{restaurant:slug}', [RestaurantController::class, 'show'])->name('restaurants.show');

// === AUTHENTICATED ROUTES ===
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // Orders (customer)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Favorites (AJAX)
    Route::post('/restaurants/{restaurant:slug}/favorite', [RestaurantController::class, 'toggleFavorite'])->name('restaurants.favorite');
});

// === ADMIN ROUTES ===
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-data', [AdminController::class, 'dashboardData'])->name('dashboard.data');

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');

    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

    // Restaurants
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
    Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
    Route::get('/restaurants/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('restaurants.edit');
    Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
    Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->name('restaurants.destroy');

    // Menu Items
    Route::get('/menu-items', [MenuItemController::class, 'index'])->name('menu-items.index');
    Route::get('/menu-items/create', [MenuItemController::class, 'create'])->name('menu-items.create');
    Route::post('/menu-items', [MenuItemController::class, 'store'])->name('menu-items.store');
    Route::get('/menu-items/{menuItem}/edit', [MenuItemController::class, 'edit'])->name('menu-items.edit');
    Route::put('/menu-items/{menuItem}', [MenuItemController::class, 'update'])->name('menu-items.update');
    Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');

    // Orders
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// === API ROUTES ===
Route::prefix('api/v1')->name('api.')->group(function () {
    Route::get('/restaurants', [MenuApiController::class, 'restaurants'])->name('restaurants');
    Route::get('/restaurants/{restaurant:slug}/menu', [MenuApiController::class, 'restaurantMenu'])->name('restaurant.menu');
    Route::get('/categories', [MenuApiController::class, 'categories'])->name('categories');
    Route::get('/search', [MenuApiController::class, 'search'])->name('search');
});
