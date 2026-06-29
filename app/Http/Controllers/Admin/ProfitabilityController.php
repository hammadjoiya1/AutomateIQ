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

        $proPriceCents = (int) config('metrics.prices.pro_monthly');
        $teamPriceCents = (int) config('metrics.prices.team_monthly');

        $mrrCents = ($proUsers * $proPriceCents) + ($teamUsers * $teamPriceCents);

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

        // Pro Plan metrics
        $proActiveUserIds = User::whereIn('id', $activeUserIds)->where('plan', 'pro')->pluck('id');
        $proActiveCount = $proActiveUserIds->count();
        $proCostCents = (int) UsageDaily::where('date', '>=', now()->subDays(30)->toDateString())
            ->whereIn('user_id', $proActiveUserIds)
            ->sum('estimated_cost_cents');
        $proAvgCostCents = $proActiveCount > 0 ? (int) ceil($proCostCents / $proActiveCount) : 0;

        // Team Plan metrics
        $teamActiveUserIds = User::whereIn('id', $activeUserIds)->where('plan', 'team')->pluck('id');
        $teamActiveCount = $teamActiveUserIds->count();
        $teamCostCents = (int) UsageDaily::where('date', '>=', now()->subDays(30)->toDateString())
            ->whereIn('user_id', $teamActiveUserIds)
            ->sum('estimated_cost_cents');
        $teamAvgCostCents = $teamActiveCount > 0 ? (int) ceil($teamCostCents / $teamActiveCount) : 0;

        $activePaidUsers = $proActiveCount + $teamActiveCount;
        $avgCostPerPaidUserCents = $activePaidUsers > 0 ? (int) ceil($costCents / $activePaidUsers) : 0;

        $creditPriceCents = (int) Setting::get('credits.credit_price_cents', config('credits.credit_price_cents'));
        if ($creditPriceCents <= 0) {
            $creditPriceCents = 10;
        }

        $proCredits = (int) Setting::get('credits.monthly_credits.pro', config('credits.monthly_credits.pro'));
        $teamCredits = (int) Setting::get('credits.monthly_credits.team', config('credits.monthly_credits.team'));

        // Recommended credits to guarantee target margin based on plan pricing
        $proRecommendedCredits = (int) ($proPriceCents / $creditPriceCents);
        $teamRecommendedCredits = (int) ($teamPriceCents / $creditPriceCents);

        // Implied margin calculated using actual plan price and average active user cost
        $proMargin = $proActiveCount > 0 ? 1 - ($proAvgCostCents / $proPriceCents) : null;
        $teamMargin = $teamActiveCount > 0 ? 1 - ($teamAvgCostCents / $teamPriceCents) : null;

        // 6-Month Historical Revenue and Cost data for ApexCharts
        $monthlyCosts = [];
        $monthlyRevenues = [];
        $monthLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthStart = $monthDate->startOfMonth()->toDateString();
            $monthEnd = $monthDate->endOfMonth()->toDateString();
            
            $monthLabels[] = $monthDate->format('M Y');
            
            $cost = (int) UsageDaily::whereBetween('date', [$monthStart, $monthEnd])->sum('estimated_cost_cents');
            $monthlyCosts[] = (float) ($cost / 100);
            
            $proCount = User::where('plan', 'pro')->where('created_at', '<=', $monthEnd)->count();
            $teamCount = User::where('plan', 'team')->where('created_at', '<=', $monthEnd)->count();
            $rev = ($proCount * $proPriceCents) + ($teamCount * $teamPriceCents);
            $monthlyRevenues[] = (float) ($rev / 100);
        }

        // Top 5 Heavy Users in the last 30 days
        $heavyUsersData = UsageDaily::where('date', '>=', now()->subDays(30)->toDateString())
            ->selectRaw('user_id, sum(estimated_cost_cents) as total_cost_cents, sum(tool_runs) as total_runs, sum(video_generations) as total_videos')
            ->groupBy('user_id')
            ->orderByDesc('total_cost_cents')
            ->take(5)
            ->get();
            
        $heavyUsers = [];
        foreach ($heavyUsersData as $row) {
            $user = User::find($row->user_id);
            if ($user) {
                $planPrice = (int) config('metrics.prices.' . $user->plan . '_monthly', 0);
                $margin = $planPrice > 0 ? 1 - ($row->total_cost_cents / $planPrice) : -1;
                
                $heavyUsers[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'plan' => $user->plan,
                    'total_cost_cents' => $row->total_cost_cents,
                    'total_runs' => $row->total_runs,
                    'total_videos' => $row->total_videos,
                    'margin' => $margin
                ];
            }
        }

        return view('admin.profitability', compact(
            'proActiveCount',
            'teamActiveCount',
            'proAvgCostCents',
            'teamAvgCostCents',
            'proPriceCents',
            'teamPriceCents',
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
            'creditPriceCents',
            'proCredits',
            'teamCredits',
            'proRecommendedCredits',
            'teamRecommendedCredits',
            'proMargin',
            'teamMargin',
            'monthlyCosts',
            'monthlyRevenues',
            'monthLabels',
            'heavyUsers'
        ));
    }

    public function applyRecommendations(Request $request)
    {
        $creditPriceCents = (int) Setting::get('credits.credit_price_cents', config('credits.credit_price_cents'));
        if ($creditPriceCents <= 0) {
            $creditPriceCents = 10;
        }

        $proRecommended = (int) (config('metrics.prices.pro_monthly') / $creditPriceCents);
        $teamRecommended = (int) (config('metrics.prices.team_monthly') / $creditPriceCents);

        $applyTo = $request->input('apply_to', 'both');
        if (in_array($applyTo, ['pro', 'both'], true)) {
            Setting::set('credits.monthly_credits.pro', $proRecommended);
        }
        if (in_array($applyTo, ['team', 'both'], true)) {
            Setting::set('credits.monthly_credits.team', $teamRecommended);
        }

        return back()->with('success', 'Recommended monthly credits applied.');
    }
}
