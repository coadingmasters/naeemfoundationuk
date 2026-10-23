<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A basket mixing one-off and recurring gifts is paid as one PayPal
 * subscription whose setup fee carries the one-off part (charged once) and
 * whose regular price is only the recurring part. So:
 *  - a plan is now unique per (mode, currency, amount, interval, setup_fee);
 *  - a donation records the per-period amount separately from what was
 *    charged on day one (total).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paypal_plans', function (Blueprint $table) {
            $table->decimal('setup_fee', 12, 2)->default(0)->after('interval');
            $table->dropUnique(['mode', 'currency', 'amount', 'interval']);
            $table->unique(['mode', 'currency', 'amount', 'interval', 'setup_fee']);
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->decimal('recurring_amount', 12, 2)->nullable()->after('total');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('recurring_amount');
        });

        Schema::table('paypal_plans', function (Blueprint $table) {
            $table->dropUnique(['mode', 'currency', 'amount', 'interval', 'setup_fee']);
            $table->unique(['mode', 'currency', 'amount', 'interval']);
            $table->dropColumn('setup_fee');
        });
    }
};
