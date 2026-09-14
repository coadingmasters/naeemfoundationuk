<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use \App\Models\Concerns\BelongsToRegion;

    protected $fillable = [
        'region',
        'name',
        'phone',
        'email',
        'postcode',
        'address',
        'cv_path',
    ];
}
