<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Support\Facades\DB;

class ToolAnalyticsController extends Controller
{
    public function index()
    {
        $stats = DB::table('tool_runs')
            ->leftJoin('users', 'tool_runs.user_id', '=', 'users.id')
            ->select(
                'tool_runs.tool_id',
                DB::raw('COUNT(*) as total_runs'),
                DB::raw("SUM(CASE WHEN tool_runs.status = 'completed' THEN 1 ELSE 0 END) as successful_runs"),
                DB::raw("SUM(CASE WHEN tool_runs.status = 'failed' THEN 1 ELSE 0 END) as failed_runs"),
                DB::raw('AVG(tool_runs.tokens_used) as avg_tokens'),
                DB::raw('AVG(tool_runs.cost_credits) as avg_cost_credits'),
                DB::raw("SUM(CASE WHEN users.plan IN ('pro', 'team') THEN 1 ELSE 0 END) as paid_runs"),
                DB::raw("SUM(CASE WHEN users.trial_ends_at IS NOT NULL AND users.trial_ends_at > NOW() THEN 1 ELSE 0 END) as trial_runs")
            )
            ->groupBy('tool_runs.tool_id')
            ->get()
            ->keyBy('tool_id');

        $tools = Tool::where('status', true)
            ->orderBy('name')
            ->get()
            ->map(function ($tool) use ($stats) {
                $toolStat = $stats->get($tool->id);
                $totalRuns = (int) ($toolStat->total_runs ?? 0);
                $successfulRuns = (int) ($toolStat->successful_runs ?? 0);
                $paidRuns = (int) ($toolStat->paid_runs ?? 0) + (int) ($toolStat->trial_runs ?? 0);

                return [
                    'tool' => $tool,
                    'total_runs' => $totalRuns,
                    'success_rate' => $totalRuns > 0 ? round(($successfulRuns / $totalRuns) * 100, 1) : 0,
                    'avg_tokens' => $toolStat ? round((float) $toolStat->avg_tokens, 1) : 0,
                    'avg_cost_credits' => $toolStat ? round((float) $toolStat->avg_cost_credits, 2) : 0,
                    'paid_share' => $totalRuns > 0 ? round(($paidRuns / $totalRuns) * 100, 1) : 0,
                ];
            });

        return view('admin.tools.analytics', compact('tools'));
    }
}
