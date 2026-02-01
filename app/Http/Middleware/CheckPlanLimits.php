<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPlanLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Admin bypass
        if ($user->role === 'admin') {
            return $next($request);
        }

            $trialActive = $user->trial_ends_at && now()->lt($user->trial_ends_at);
            $isPro = in_array($user->plan, ['pro', 'team']) || $trialActive;
            $plan = $isPro ? 'pro' : 'free';

        if ($feature === 'tool_run') {
            $limit = $plan === 'pro' ? 200 : 5;
            $todayUsage = $user->toolRuns()->whereDate('created_at', now())->count();

            if ($todayUsage >= $limit) {
                return response()->json([
                    'status' => 'error',
                    'message' => "You have reached your daily limit of {$limit} generations. Upgrade to Pro for more."
                ], 403);
            }
        }

        if ($feature === 'workflow') {
                if (!$isPro) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Workflows are a Pro feature.'], 403);
                }
                return redirect()->route('pricing')->with('error', 'Automation Workflows are available on the Pro plan.');
            }
        }

        if ($feature === 'video_generation') {
                $limit = $isPro ? 50 : 1;
            $todayUsage = $user->videoProjects()->whereDate('created_at', now())->count();

            if ($todayUsage >= $limit) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => "You have reached your daily video limit of {$limit}. Upgrade to Pro for more."
                    ], 403);
                }

                return redirect()->route('pricing')
                    ->with('error', "You have reached your daily video limit of {$limit}. Upgrade to Pro for more.");
            }
        }

        if ($feature === 'library_storage') {
                if (!$isPro) {
                $limit = 20;
                // Count items across all collections
                $totalItems = 0;
                foreach ($user->collections as $collection) {
                    $totalItems += $collection->items()->count();
                }

                if ($totalItems >= $limit) {
                    return back()->with('error', "You have reached your storage limit of {$limit} items. Upgrade to Pro for unlimited storage.");
                }
            }
        }

        return $next($request);
    }
}
