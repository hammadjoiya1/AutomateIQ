<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;
use App\Models\LifecycleEmailLog;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('lifecycle:send', function () {
    $now = now();

    $sendEmail = function (User $user, string $type, string $subject, string $body) {
        $exists = LifecycleEmailLog::where('user_id', $user->id)->where('type', $type)->exists();
        if ($exists) return;

        Mail::raw($body, function ($message) use ($user, $subject) {
            $message->to($user->email)->subject($subject);
        });

        LifecycleEmailLog::create([
            'user_id' => $user->id,
            'type' => $type,
            'sent_at' => now(),
        ]);
    };

    // Day 2
    User::whereDate('created_at', $now->copy()->subDays(2))->get()->each(function (User $user) use ($sendEmail) {
        $sendEmail(
            $user,
            'day2',
            'Your next 5‑minute win',
            'Tip: Run your next tool, save it to the Library, and reuse it for faster output. Your trial credits are waiting.'
        );
    });

    // Day 7
    User::whereDate('created_at', $now->copy()->subDays(7))->get()->each(function (User $user) use ($sendEmail) {
        $sendEmail(
            $user,
            'day7',
            'Automate your workflow',
            'Unlock workflows to schedule content runs and scale output. Upgrade to Pro to automate end‑to‑end.'
        );
    });

    // Trial ending (1 day before)
    User::whereNotNull('trial_ends_at')
        ->whereDate('trial_ends_at', $now->copy()->addDay())
        ->get()
        ->each(function (User $user) use ($sendEmail) {
            $sendEmail(
                $user,
                'trial_ending',
                'Your trial ends tomorrow',
                'Upgrade now to keep your limits unlocked and avoid interruptions.'
            );
        });
})->purpose('Send lifecycle emails');

Schedule::command('lifecycle:send')->dailyAt('09:00');
