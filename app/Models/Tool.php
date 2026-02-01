<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'input_schema' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function runs()
    {
        return $this->hasMany(ToolRun::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tool_tag')->withTimestamps();
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'tool_favorites')->withTimestamps();
    }
}
