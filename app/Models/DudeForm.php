<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DudeForm extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'form', 'content'];

    protected $casts = ['content' => 'array'];
}
