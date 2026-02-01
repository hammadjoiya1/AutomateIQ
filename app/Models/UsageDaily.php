<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageDaily extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'tool_runs',
        'video_generations',
        'workflow_runs',
        'overage_tool_runs',
        'overage_video_generations',
        'estimated_cost_cents',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
