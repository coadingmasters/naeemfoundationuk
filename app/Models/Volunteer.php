<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'skillsets',
        'additional_info',
    ];

    protected function casts(): array
    {
        return [
            'skillsets' => 'array',
        ];
    }
}
