<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMonthlyCredits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || $user->role === 'admin') {
            return $next($request);
        }

        $plan = $user->plan ?? 'free';
        $trialActive = $user->trial_ends_at && now()->lt($user->trial_ends_at);
        if ($trialActive && $plan === 'free') {
            $plan = 'pro';
        }

        $monthlyCredits = (int) (Setting::get("credits.monthly_credits.{$plan}", config("credits.monthly_credits.{$plan}")) ?? 0);
        if ($monthlyCredits <= 0) {
            return $next($request);
        }

        $lastGrant = $user->last_credit_grant_at;
        if ($lastGrant && $lastGrant->isSameMonth(now())) {
            return $next($request);
        }

        $user->grantSubscriptionCredits($monthlyCredits);

        return $next($request);
    }
}
