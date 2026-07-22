<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use \App\Models\Concerns\BelongsToRegion;

    protected $fillable = [
        'region',
        'reference',
        'first_name',
        'last_name',
        'email',
        'phone',
        'billing_address',
        'gift_aid',
        'on_behalf_of_organisation',
        'organisation_name',
        'cover_fee',
        'items',
        'currency',
        'subtotal',
        'fee',
        'total',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
        'gift_aid' => 'boolean',
        'on_behalf_of_organisation' => 'boolean',
        'cover_fee' => 'boolean',
        'subtotal' => 'decimal:2',
        'fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];
}
