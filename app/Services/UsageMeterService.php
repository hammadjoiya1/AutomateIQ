<?php

namespace App\Services;

use App\Models\UsageDaily;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsageMeterService
{
    /**
     * Atomically ensure a usage row exists for today.
     *
     * insertOrIgnore maps to SQLite's "INSERT OR IGNORE INTO", which is a
     * single atomic statement – it inserts the row or silently does nothing
     * if (user_id, date) already exists. No race condition possible.
     */
    private function getOrCreateUsage(User $user, string $date): UsageDaily
    {
        DB::table('usage_dailies')->insertOrIgnore([
            'user_id'                  => $user->id,
            'date'                     => $date,
            'tool_runs'                => 0,
            'video_generations'        => 0,
            'workflow_runs'            => 0,
            'overage_tool_runs'        => 0,
            'overage_video_generations'=> 0,
            'estimated_cost_cents'     => 0,
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        return UsageDaily::where('user_id', $user->id)
            ->where('date', $date)
            ->first();
    }

    public function recordToolRun(User $user, bool $isVideo = false, int $costCents = 0, int $creditsCharged = 0): void
    {
        $date  = now()->toDateString();
        $usage = $this->getOrCreateUsage($user, $date);

        $trialActive = $user->trial_ends_at && now()->lt($user->trial_ends_at);
        $isPro = in_array($user->plan, ['pro', 'team']) || $trialActive;

        if ($isVideo) {
            $usage->video_generations += 1;
            $usage->estimated_cost_cents += $costCents;
            if ($isPro && $usage->video_generations > (int) config('metrics.limits.video_pro')) {
                $usage->overage_video_generations += 1;
            }
        } else {
            $usage->tool_runs += 1;
            $usage->estimated_cost_cents += $costCents;
            if ($isPro && $usage->tool_runs > (int) config('metrics.limits.tool_runs_pro')) {
                $usage->overage_tool_runs += 1;
            }
        }

        $usage->save();
    }

    public function recordWorkflowRun(User $user): void
    {
        $date  = now()->toDateString();
        $usage = $this->getOrCreateUsage($user, $date);

        $usage->workflow_runs += 1;
        $usage->estimated_cost_cents += (int) config('metrics.costs.workflow_run_cents');
        $usage->save();
    }
}
