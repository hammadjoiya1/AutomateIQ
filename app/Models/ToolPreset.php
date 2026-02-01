<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolPreset extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_id',
        'user_id',
        'name',
        'input_data',
        'visibility',
    ];

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
