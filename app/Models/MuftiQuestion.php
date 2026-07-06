<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuftiQuestion extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
    ];
}
