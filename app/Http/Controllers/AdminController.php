<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_tools' => \App\Models\Tool::count(),
            'total_runs' => \App\Models\ToolRun::count(),
            'active_workflows' => \App\Models\Workflow::where('active', true)->count(),
            'recent_activity' => \App\Models\VideoProject::with('user')->latest()->take(5)->get(),
            'failed_jobs' => \App\Models\VideoProject::where('status', 'failed')->count(),
            'pending_jobs' => \App\Models\VideoProject::whereIn('status', ['pending', 'processing', 'generating'])->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}
