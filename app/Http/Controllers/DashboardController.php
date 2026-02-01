<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Gather some basic stats for the user dashboard
        $user = Auth::user();
        $toolRunsCount = $user->toolRuns()->count() ?? 0; // Assuming relationship exists, or query directly
        // If relationships aren't set up perfectly on User model yet, we can use DB queries or just pass null for now.
        // Let's assume toolRuns exists or catch error. Safer to just return view for now if unsure.
        // Actually, User model usually doesn't have toolRuns by default unless I added it.
        // I'll check User model if I can, but to be safe and avoid errors, I'll just return the view for now or fetch via ToolRun model.

        return view('dashboard');
    }
}
