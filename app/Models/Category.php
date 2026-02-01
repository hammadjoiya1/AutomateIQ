<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tools()
    {
        return $this->hasMany(Tool::class);
    }

    public function posts()
    {
        return $this->hasMany(BlogPost::class);
    }
}
