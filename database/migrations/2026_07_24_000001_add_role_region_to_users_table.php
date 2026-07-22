<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 'super_admin' sees every region; 'admin' is locked to a single region.
            $table->string('role')->default('admin')->after('is_admin');
            // Region code (GB/US/CA) for region admins; null for super admins.
            $table->string('region', 3)->nullable()->after('role');
        });

        // Existing administrators become super admins (full access to all regions).
        DB::table('users')->where('is_admin', true)->update([
            'role' => 'super_admin',
            'region' => null,
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'region']);
        });
    }
};
