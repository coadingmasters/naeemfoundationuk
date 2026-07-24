<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use \App\Models\Concerns\BelongsToRegion;

    protected $fillable = [
        'region',
        'reference',
        'name',
        'email',
        'phone',
        'address',
        'postcode',
        'items',
        'subtotal',
        'currency',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'subtotal' => 'decimal:2',
        ];
    }

    /** Display symbol for the currency this order was placed in. */
    public function currencySymbol(): string
    {
        return ['GBP' => '£', 'USD' => '$', 'CAD' => 'CA$'][$this->currency] ?? '£';
    }
}
