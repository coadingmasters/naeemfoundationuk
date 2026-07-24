<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuftiQuestion extends Model
{
    use \App\Models\Concerns\BelongsToRegion;

    protected $fillable = [
        'region',
        'name',
        'email',
        'phone',
        'message',
        'answer',
        'answered_at',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];
}
