<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UsageDaily;
use App\Models\User;
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

        $overageTool = (int) $last30->sum('overage_tool_runs') * (int) config('metrics.overage.tool_run_cents');
        $overageVideo = (int) $last30->sum('overage_video_generations') * (int) config('metrics.overage.video_generation_cents');
        $overageCents = $overageTool + $overageVideo;

        return view('admin.profitability', compact(
            'proUsers',
            'teamUsers',
            'mrrCents',
            'costCents',
            'toolRuns',
            'videoRuns',
            'workflowRuns',
            'overageCents'
        ));
    }
}
