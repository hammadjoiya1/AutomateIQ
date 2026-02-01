<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LifecycleEmailLog extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
