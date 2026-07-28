<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A cached PayPal monthly billing plan for a given (mode, currency, amount),
 * so we don't recreate the same plan on PayPal for every recurring gift.
 */
class PayPalPlan extends Model
{
    protected $table = 'paypal_plans';

    protected $fillable = [
        'mode',
        'currency',
        'amount',
        'interval',
        'product_id',
        'plan_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
