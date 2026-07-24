<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

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
        Schema::defaultStringLength(191);

        RedirectIfAuthenticated::redirectUsing(function (Request $request) {
            if ($request->user() && $request->user()->isEmployee()) {
                return route('employee.dashboard');
            }

            return route('admin.dashboard');
        });

        // ── Authorization Gates ──────────────────────────────────

        Gate::define('access-admin', fn (User $user) => $user->isAdmin());

        Gate::define('is-employee', fn (User $user) => $user->isEmployee());

        Gate::define('view-employees', fn (User $user) => $user->isAdmin());

        Gate::define('manage-payroll', fn (User $user) => $user->isAdmin());

        Gate::define('manage-leaves', fn (User $user) => $user->isAdmin());

        Gate::define('manage-settings', fn (User $user) => $user->isAdmin());

        /**
         * Employees can only view their own leave requests.
         * Admins can view all.
         */
        Gate::define('view-leave', function (User $user, $leaveRequest) {
            if ($user->isAdmin()) {
                return true;
            }

            return $leaveRequest->user_id === $user->id;
        });

        /**
         * Employees can only view their own payslips (via linked Employee).
         */
        Gate::define('view-payslip', function (User $user, $payroll) {
            if ($user->isAdmin()) {
                return true;
            }

            $employee = $user->employee;

            return $employee && $payroll->employee_id === $employee->id;
        });
    }
}
