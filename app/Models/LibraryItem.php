<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'meta_data' => 'array',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
