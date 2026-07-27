<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Recurring (monthly) giving. A donation is either a one-off capture or a
 * PayPal subscription that auto-deducts each month.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('frequency', 20)->default('one-off')->after('cover_fee');
            $table->string('subscription_id', 64)->nullable()->unique()->after('payment_id');
            $table->string('subscription_status', 30)->nullable()->after('subscription_id');
            $table->timestamp('next_billing_at')->nullable()->after('subscription_status');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropUnique(['subscription_id']);
            $table->dropColumn(['frequency', 'subscription_id', 'subscription_status', 'next_billing_at']);
        });
    }
};
