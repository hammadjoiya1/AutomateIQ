<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'config' => 'array',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
