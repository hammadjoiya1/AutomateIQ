<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function show(Request $request): View
    {
        return view('onboarding');
    }

    public function complete(Request $request): RedirectResponse
    {
        $user = $request->user();
        $user->onboarding_completed_at = now();
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Onboarding complete.');
    }
}
