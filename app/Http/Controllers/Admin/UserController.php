<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $stats = [
            'total_runs' => $user->toolRuns()->count(),
            'last_login' => $user->updated_at->diffForHumans() ?? 'N/A',
        ];
        return view('admin.users.show', compact('user', 'stats'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,user',
            'plan' => 'required|string',
            'subscription_credits' => 'required|integer',
            'topup_credits' => 'required|integer',
        ]);

        $user->role = $validated['role'];
        $user->plan = $validated['plan'];
        $user->subscription_credits = $validated['subscription_credits'];
        $user->topup_credits = $validated['topup_credits'];
        $user->recomputeCredits();
        $user->save();

        return back()->with('success', 'User updated successfully.');
    }

    public function ban(User $user)
    {
        $user->update(['is_banned' => true]);
        return back()->with('success', 'User has been banned.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);
        return back()->with('success', 'User has been unbanned.');
    }
}
