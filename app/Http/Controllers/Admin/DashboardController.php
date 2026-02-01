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

        return view('admin.dashboard', compact('stats'));
    }
}
