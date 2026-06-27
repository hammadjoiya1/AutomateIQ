<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'active_users' => \App\Models\User::where('updated_at', '>=', now()->subDays(7))->count(),
            'total_tools' => \App\Models\Tool::count(),
            'active_tools' => \App\Models\Tool::where('status', true)->count(),
            'total_runs' => \App\Models\ToolRun::count(),
            'runs_today' => \App\Models\ToolRun::whereDate('created_at', today())->count(),
            'total_workflows' => \App\Models\Workflow::count(),
            'failed_jobs' => \App\Models\VideoProject::where('status', 'failed')->count(),
            'pending_jobs' => \App\Models\VideoProject::whereIn('status', ['pending', 'processing', 'generating'])->count(),
            'contact_messages' => \App\Models\ContactMessage::where('is_read', false)->count(),
            'recent_activity' => \App\Models\VideoProject::with('user')->latest()->take(5)->get(),
        ];

        // 1. Query Tool Runs Timeline (Last 14 days)
        $dailyRuns = \App\Models\ToolRun::select(
            \Illuminate\Support\Facades\DB::raw('DATE(created_at) as date'),
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'),
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"),
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed")
        )
        ->where('created_at', '>=', now()->subDays(14))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // 2. Query User Signups Timeline (Last 14 days)
        $dailySignups = \App\Models\User::select(
            \Illuminate\Support\Facades\DB::raw('DATE(created_at) as date'),
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', now()->subDays(14))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // 3. Query Tool Usage Share (Top 5)
        $toolUsage = \App\Models\ToolRun::select('tool_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->with('tool')
            ->groupBy('tool_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Generate filled timeline of last 14 days
        $timelineData = [];
        for ($i = 13; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $timelineData[$dateStr] = [
                'label' => now()->subDays($i)->format('M d'),
                'total_runs' => 0,
                'completed_runs' => 0,
                'failed_runs' => 0,
                'new_users' => 0,
            ];
        }

        foreach ($dailyRuns as $run) {
            if (isset($timelineData[$run->date])) {
                $timelineData[$run->date]['total_runs'] = (int) $run->total;
                $timelineData[$run->date]['completed_runs'] = (int) $run->completed;
                $timelineData[$run->date]['failed_runs'] = (int) $run->failed;
            }
        }

        foreach ($dailySignups as $signup) {
            if (isset($timelineData[$signup->date])) {
                $timelineData[$signup->date]['new_users'] = (int) $signup->total;
            }
        }

        $timeline = array_values($timelineData);

        return view('admin.dashboard', compact('stats', 'timeline', 'toolUsage'));
    }
}
