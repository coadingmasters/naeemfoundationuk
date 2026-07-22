<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use \App\Models\Concerns\BelongsToRegion;

    protected $fillable = [
        'region',
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
