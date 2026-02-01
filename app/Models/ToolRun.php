<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolRun extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'input_data' => 'array',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
