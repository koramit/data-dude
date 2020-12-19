<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dude extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'target', 'body'];
}
