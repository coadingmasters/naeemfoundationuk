<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Recurring gifts can now be weekly as well as monthly, so a plan is unique per
 * (mode, currency, amount, interval) — e.g. £10 WEEK and £10 MONTH are two plans.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paypal_plans', function (Blueprint $table) {
            $table->string('interval', 10)->default('MONTH')->after('amount');
            $table->dropUnique(['mode', 'currency', 'amount']);
            $table->unique(['mode', 'currency', 'amount', 'interval']);
        });
    }

    public function down(): void
    {
        Schema::table('paypal_plans', function (Blueprint $table) {
            $table->dropUnique(['mode', 'currency', 'amount', 'interval']);
            $table->unique(['mode', 'currency', 'amount']);
            $table->dropColumn('interval');
        });
    }
};
