<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentiRecord extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'encountered_at' => 'datetime',
        'dismissed_at' => 'datetime',
        'tagged_med_at' => 'datetime',
    ];
}
