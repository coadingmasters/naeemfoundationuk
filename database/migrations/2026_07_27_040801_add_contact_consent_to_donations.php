<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores the donor's "keep in touch" marketing consent (email / phone / SMS),
 * gathered on the make-a-donation wizard. Null means the donor never saw the
 * step (e.g. a quick widget donation).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->json('contact_consent')->nullable()->after('gift_aid');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('contact_consent');
        });
    }
};
