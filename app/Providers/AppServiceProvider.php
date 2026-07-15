<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated; // Add this line!

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
        // Force the default guest/RedirectIfAuthenticated redirect to the login page
        RedirectIfAuthenticated::redirectUsing(function () {
            return route('login');
        });
    }
}
