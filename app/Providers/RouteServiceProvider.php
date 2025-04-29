<?php

namespace App\Providers;

use App\Models\Ads;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
        view()->composer('welcome', function ($view) {
            $view->with('ads',Ads::get()
                // ->where('start_date', '<=', now())
                // ->where('end_date', '>=', now())
                // ->latest()
                // ->take(3)
                );
        });
    }

    public static function home()
    {
        if (auth()->check()) {
            return auth()->user()->role === 'admin'
                ? route('admin.dashboard')
                : route('user.dashboard');
        }
        return '/';
    }
    public static function redirectTo()
    {
        // if (auth()->user()->role === 'admin') {
        //     return route('admin.dashboard');
        // }
        return route('dashboard');
    }
}
