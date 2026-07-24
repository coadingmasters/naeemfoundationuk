<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('donations')) {
            Schema::table('donations', function (Blueprint $table) {
                if (! Schema::hasColumn('donations', 'city')) {
                    $table->string('city')->nullable()->after('billing_address');
                }
                if (! Schema::hasColumn('donations', 'postcode')) {
                    $table->string('postcode', 20)->nullable()->after('city');
                }
            });
        }

        if (Schema::hasTable('orders') && ! Schema::hasColumn('orders', 'postcode')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('postcode', 20)->nullable()->after('address');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('donations')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropColumn(['city', 'postcode']);
            });
        }
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'postcode')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('postcode');
            });
        }
    }
};
