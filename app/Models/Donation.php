<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRegion;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use BelongsToRegion;

    protected $fillable = [
        'region',
        'reference',
        'first_name',
        'last_name',
        'email',
        'phone',
        'billing_address',
        'city',
        'postcode',
        'gift_aid',
        'contact_consent',
        'on_behalf_of_organisation',
        'organisation_name',
        'cover_fee',
        'frequency',
        'items',
        'currency',
        'subtotal',
        'fee',
        'total',
        'status',
        'payment_provider',
        'payment_id',
        'subscription_id',
        'recurring_amount',
        'subscription_status',
        'next_billing_at',
        'paid_at',
    ];

    protected $casts = [
        'items' => 'array',
        'contact_consent' => 'array',
        'paid_at' => 'datetime',
        'next_billing_at' => 'datetime',
        'gift_aid' => 'boolean',
        'on_behalf_of_organisation' => 'boolean',
        'cover_fee' => 'boolean',
        'subtotal' => 'decimal:2',
        'fee' => 'decimal:2',
        'total' => 'decimal:2',
        'recurring_amount' => 'decimal:2',
    ];
}
