<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
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
        Blade::if('isManager', function () {
            return Auth()->user()->role->role_name == "Manager";
        });

        Gate::define('isManager', function ($user) {
            return $user->role->role_name == "Manager";
        });

        Gate::define('rejectOvertime', function ($user, $overtime) {
            return $user->role->role_name == "Manager" && $user->department_id == $overtime->user->department_id;
        });

        Gate::define('viewOvertime', function ($user, $overtime) {
            return $user->role->role_name == "Manager" || $user->id == $overtime->user_id;
        });

        Gate::define('viewAttendance', function ($user, $attendance) {
            return $user->role->role_name == "Manager" || $user->id == $attendance->user_id;
        });
    }
}
