<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
<<<<<<< HEAD
use Illuminate\Support\Facades\Schema;
=======
use Illuminate\Auth\Middleware\RedirectIfAuthenticated; // Add this line!
>>>>>>> origin/feat/be-leave-chenglim

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
<<<<<<< HEAD
        Schema::defaultStringLength(191);
=======
        // Force the default guest/RedirectIfAuthenticated redirect to the login page
        RedirectIfAuthenticated::redirectUsing(function () {
            return route('login');
        });
>>>>>>> origin/feat/be-leave-chenglim
    }
}