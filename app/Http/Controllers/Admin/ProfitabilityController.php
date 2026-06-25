<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\UsageDaily;
use App\Models\User;
use App\Models\ToolRun;
use App\Services\CreditPricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProfitabilityController extends Controller
{
    public function index(): View
    {
        $proUsers = User::where('plan', 'pro')->count();
        $teamUsers = User::where('plan', 'team')->count();

        $mrrCents = ($proUsers * (int) config('metrics.prices.pro_monthly'))
            + ($teamUsers * (int) config('metrics.prices.team_monthly'));

        $last30 = UsageDaily::where('date', '>=', now()->subDays(30)->toDateString());
        $costCents = (int) $last30->sum('estimated_cost_cents');
        $toolRuns = (int) $last30->sum('tool_runs');
        $videoRuns = (int) $last30->sum('video_generations');
        $workflowRuns = (int) $last30->sum('workflow_runs');

        $creditsCharged = 0;
        if (Schema::hasColumn('tool_runs', 'credits_charged')) {
            $creditsCharged = (int) ToolRun::where('created_at', '>=', now()->subDays(30))
                ->sum('credits_charged');
        }

        $overageTool = (int) $last30->sum('overage_tool_runs') * (int) config('metrics.overage.tool_run_cents');
        $overageVideo = (int) $last30->sum('overage_video_generations') * (int) config('metrics.overage.video_generation_cents');
        $overageCents = $overageTool + $overageVideo;

        $pricing = app(CreditPricingService::class);
        $activeUserIds = UsageDaily::where('date', '>=', now()->subDays(30)->toDateString())
            ->distinct('user_id')
            ->pluck('user_id');
        $activePaidUsers = User::whereIn('id', $activeUserIds)
            ->whereIn('plan', ['pro', 'team'])
            ->count();

        $avgCostPerPaidUserCents = $activePaidUsers > 0 ? (int) ceil($costCents / $activePaidUsers) : 0;
        $recommendedCredits = $avgCostPerPaidUserCents > 0
            ? $pricing->creditsForCostCents($avgCostPerPaidUserCents)
            : 0;

        $creditPriceCents = (int) Setting::get('credits.credit_price_cents', config('credits.credit_price_cents'));
        $proCredits = (int) Setting::get('credits.monthly_credits.pro', config('credits.monthly_credits.pro'));
        $teamCredits = (int) Setting::get('credits.monthly_credits.team', config('credits.monthly_credits.team'));
        $proRevenueCents = $proCredits * $creditPriceCents;
        $teamRevenueCents = $teamCredits * $creditPriceCents;

        $proMargin = $proRevenueCents > 0 ? 1 - ($avgCostPerPaidUserCents / $proRevenueCents) : null;
        $teamMargin = $teamRevenueCents > 0 ? 1 - ($avgCostPerPaidUserCents / $teamRevenueCents) : null;

        return view('admin.profitability', compact(
            'proUsers',
            'teamUsers',
            'mrrCents',
            'costCents',
            'toolRuns',
            'videoRuns',
            'workflowRuns',
            'overageCents',
            'creditsCharged',
            'activePaidUsers',
            'avgCostPerPaidUserCents',
            'recommendedCredits',
            'creditPriceCents',
            'proCredits',
            'teamCredits',
            'proRevenueCents',
            'teamRevenueCents',
            'proMargin',
            'teamMargin'
        ));
    }

    public function applyRecommendations(Request $request)
    {
        $pricing = app(CreditPricingService::class);

        $last30 = UsageDaily::where('date', '>=', now()->subDays(30)->toDateString());
        $costCents = (int) $last30->sum('estimated_cost_cents');

        $activeUserIds = UsageDaily::where('date', '>=', now()->subDays(30)->toDateString())
            ->distinct('user_id')
            ->pluck('user_id');
        $activePaidUsers = User::whereIn('id', $activeUserIds)
            ->whereIn('plan', ['pro', 'team'])
            ->count();

        if ($activePaidUsers === 0) {
            return back()->with('error', 'No active paid users in the last 30 days.');
        }

        $avgCostPerPaidUserCents = (int) ceil($costCents / $activePaidUsers);
        $recommendedCredits = $pricing->creditsForCostCents($avgCostPerPaidUserCents);

        $applyTo = $request->input('apply_to', 'both');
        if (in_array($applyTo, ['pro', 'both'], true)) {
            Setting::set('credits.monthly_credits.pro', $recommendedCredits);
        }
        if (in_array($applyTo, ['team', 'both'], true)) {
            Setting::set('credits.monthly_credits.team', $recommendedCredits);
        }

        return back()->with('success', 'Recommended monthly credits applied.');
    }
}
