<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        $this->registerPolicies();

        // Admin gate
        Gate::define('admin-action', function ($user) {
            return $user->isAdmin();
        });

        // Owner gate: only restaurant owner
        Gate::define('owner-action', function ($user) {
            return $user->isRestaurantOwner() || $user->isAdmin();
        });
    }
}
