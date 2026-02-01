<?php

return [
    'limits' => [
        'tool_runs_free' => 5,
        'tool_runs_pro' => 100,
        'video_free' => 0,
        'video_pro' => 5,
    ],
    'costs' => [
        'tool_run_cents' => (int) env('COST_TOOL_RUN_CENTS', 5),
        'video_generation_cents' => (int) env('COST_VIDEO_GENERATION_CENTS', 150),
        'workflow_run_cents' => (int) env('COST_WORKFLOW_RUN_CENTS', 20),
    ],
    'overage' => [
        'tool_run_cents' => (int) env('OVERAGE_TOOL_RUN_CENTS', 10),
        'video_generation_cents' => (int) env('OVERAGE_VIDEO_GENERATION_CENTS', 200),
    ],
    'prices' => [
        'pro_monthly' => (int) env('PLAN_PRO_MONTHLY_CENTS', 2900),
        'team_monthly' => (int) env('PLAN_TEAM_MONTHLY_CENTS', 9900),
    ],
];
