<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoScene extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_project_id',
        'sequence_order',
        'script_text',
        'image_prompt',
        'status', // pending, generating, completed, failed
        'video_url',
        'audio_url',
        'replicate_prediction_id',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(VideoProject::class, 'video_project_id');
    }
}
