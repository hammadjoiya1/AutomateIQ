<?php

namespace App\Services;

use App\Models\UsageDaily;
use App\Models\User;

class UsageMeterService
{
    public function recordToolRun(User $user, bool $isVideo = false): void
    {
        $date = now()->toDateString();
        $usage = UsageDaily::firstOrCreate([
            'user_id' => $user->id,
            'date' => $date,
        ], [
            'tool_runs' => 0,
            'video_generations' => 0,
            'workflow_runs' => 0,
            'overage_tool_runs' => 0,
            'overage_video_generations' => 0,
            'estimated_cost_cents' => 0,
        ]);

        $trialActive = $user->trial_ends_at && now()->lt($user->trial_ends_at);
        $isPro = in_array($user->plan, ['pro', 'team']) || $trialActive;

        if ($isVideo) {
            $usage->video_generations += 1;
            $usage->estimated_cost_cents += (int) config('metrics.costs.video_generation_cents');
            if ($isPro && $usage->video_generations > (int) config('metrics.limits.video_pro')) {
                $usage->overage_video_generations += 1;
            }
        } else {
            $usage->tool_runs += 1;
            $usage->estimated_cost_cents += (int) config('metrics.costs.tool_run_cents');
            if ($isPro && $usage->tool_runs > (int) config('metrics.limits.tool_runs_pro')) {
                $usage->overage_tool_runs += 1;
            }
        }

        $usage->save();
    }

    public function recordWorkflowRun(User $user): void
    {
        $date = now()->toDateString();
        $usage = UsageDaily::firstOrCreate([
            'user_id' => $user->id,
            'date' => $date,
        ], [
            'tool_runs' => 0,
            'video_generations' => 0,
            'workflow_runs' => 0,
            'overage_tool_runs' => 0,
            'overage_video_generations' => 0,
            'estimated_cost_cents' => 0,
        ]);

        $usage->workflow_runs += 1;
        $usage->estimated_cost_cents += (int) config('metrics.costs.workflow_run_cents');
        $usage->save();
    }
}
