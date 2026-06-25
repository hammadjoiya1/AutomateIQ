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
            if ((int) $user->credits <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are out of credits. Please top up or upgrade your plan.'
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
                if ((int) $user->credits <= 0) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'You are out of credits. Please top up or upgrade your plan.'
                    ], 403);
                }

                return redirect()->route('pricing')
                    ->with('error', 'You are out of credits. Please top up or upgrade your plan.');
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
