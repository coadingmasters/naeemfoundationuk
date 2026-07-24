<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Records how a donation / order was actually paid, so every "paid" row can be
 * traced back to a real transaction in the PayPal dashboard.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('payment_provider', 30)->nullable()->after('status');
            // PayPal capture ID — unique so a replayed capture can't double-record.
            $table->string('payment_id', 64)->nullable()->unique()->after('payment_provider');
            $table->timestamp('paid_at')->nullable()->after('payment_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_provider', 30)->nullable()->after('status');
            $table->string('payment_id', 64)->nullable()->unique()->after('payment_provider');
            $table->timestamp('paid_at')->nullable()->after('payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropUnique(['payment_id']);
            $table->dropColumn(['payment_provider', 'payment_id', 'paid_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['payment_id']);
            $table->dropColumn(['payment_provider', 'payment_id', 'paid_at']);
        });
    }
};
