<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            // These photos are exported extra-wide (1600x375) so they can be
            // cropped for any screen size — where the subject actually sits
            // in that wide frame varies photo to photo (dead centre, hard
            // left, etc.), so a single site-wide crop anchor cuts different
            // slides' subjects off. Admin-settable per slide instead.
            $table->string('image_position', 10)->default('left')->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn('image_position');
        });
    }
};
