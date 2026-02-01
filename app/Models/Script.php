<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'topic',
        'tone',
        'length',
        'duration',
        'target_audience',
        'key_points',
        'script_content',
        'word_count',
        'tokens_used',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the script
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate and set word count
     */
    public function setWordCount()
    {
        $this->word_count = str_word_count(strip_tags($this->script_content));
        return $this;
    }

    /**
     * Get formatted script sections
     */
    public function getFormattedScriptAttribute()
    {
        return nl2br(e($this->script_content));
    }

    /**
     * Get estimated reading time in minutes
     */
    public function getReadingTimeAttribute()
    {
        return ceil($this->word_count / 150); // Average reading speed
    }
}
