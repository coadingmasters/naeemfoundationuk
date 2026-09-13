<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_heroes', function (Blueprint $table) {
            // Optional phone-specific banner (different crop/dimensions to the
            // desktop `image`). Falls back to the desktop photo when not set.
            $table->string('mobile_image', 1000)->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('page_heroes', function (Blueprint $table) {
            $table->dropColumn('mobile_image');
        });
    }
};
