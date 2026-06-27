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

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            foreach ($user->subscriptions as $subscription) {
                $subscription->items()->delete();
                $subscription->delete();
            }
            $user->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function impersonate(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'You cannot impersonate other administrators.');
        }

        session(['impersonated_by' => auth()->id()]);
        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'You are now logged in as ' . $user->name);
    }

    public function leaveImpersonation()
    {
        $adminId = session()->pull('impersonated_by');

        if ($adminId) {
            $admin = User::find($adminId);
            if ($admin) {
                auth()->login($admin);
                return redirect()->route('admin.users.index')->with('success', 'Returned to Admin Panel.');
            }
        }

        return redirect()->route('home');
    }
}
