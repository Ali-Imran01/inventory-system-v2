<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::define('admin-only', function ($user) {
            return $user->role === 'admin';
        });

        \Illuminate\Support\Facades\Gate::define('manage-stock', function ($user) {
            return in_array($user->role, ['admin', 'staff']);
        });

        \Illuminate\Support\Facades\Gate::define('view-only', function ($user) {
            return in_array($user->role, ['admin', 'staff', 'viewer']);
        });
    }
}
