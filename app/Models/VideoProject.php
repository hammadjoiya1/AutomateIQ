<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class VideoProject extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'prompt',
        'script_content',
        'model_provider',
        'visual_style',
        'settings',
        'status',
        'video_url',
        'thumbnail_url',
        'completed_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scenes()
    {
        return $this->hasMany(VideoScene::class);
    }
}
