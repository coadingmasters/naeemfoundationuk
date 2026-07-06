<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HajjRegistration extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'has_pakistani_passport',
    ];

    protected function casts(): array
    {
        return [
            'has_pakistani_passport' => 'boolean',
        ];
    }
}
