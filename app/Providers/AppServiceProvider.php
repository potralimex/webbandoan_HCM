<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Define admin gate directly here (no need for separate AuthServiceProvider in Laravel 11)
        Gate::define('admin-action', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('owner-action', function ($user) {
            return $user->isRestaurantOwner() || $user->isAdmin();
        });
    }
}
