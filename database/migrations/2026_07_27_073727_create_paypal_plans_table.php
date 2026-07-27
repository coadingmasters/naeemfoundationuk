<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Caches the PayPal monthly billing plan created for each (mode, currency,
 * amount) combination, so we reuse a plan instead of recreating it on PayPal
 * for every recurring gift. Scoped by mode so sandbox and live never mix.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paypal_plans', function (Blueprint $table) {
            $table->id();
            $table->string('mode', 10);              // sandbox | live
            $table->string('currency', 3);
            $table->decimal('amount', 10, 2);
            $table->string('product_id', 64);
            $table->string('plan_id', 64);
            $table->timestamps();

            $table->unique(['mode', 'currency', 'amount']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paypal_plans');
    }
};
