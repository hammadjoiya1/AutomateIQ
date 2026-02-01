<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('tool-runs', function (Request $request) {
            $user = $request->user();
            $trialActive = $user && $user->trial_ends_at && now()->lt($user->trial_ends_at);
            $isPro = $user && (in_array($user->plan, ['pro', 'team']) || $trialActive);

            $limit = $isPro ? 60 : ($user ? 25 : 10);
            $key = $user ? 'user:' . $user->id : $request->ip();

            return Limit::perMinute($limit)->by($key);
        });
    }
}
