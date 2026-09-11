<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * "Steps of Hajj" moves from 8 fixed, named slots to an open, admin-managed
 * list (add/edit/delete as many videos as needed) — mirrors hajj_videos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hajj_step_videos', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->unsignedInteger('sort_order')->default(0)->after('video_url');
        });

        // Carry over any step_key an admin had already set as a human-readable
        // title, so a video they already configured doesn't just vanish.
        DB::table('hajj_step_videos')->get()->each(function ($row) {
            DB::table('hajj_step_videos')->where('id', $row->id)->update([
                'title' => Str::headline($row->step_key),
            ]);
        });

        Schema::table('hajj_step_videos', function (Blueprint $table) {
            $table->dropUnique(['step_key']);
            $table->dropColumn('step_key');
        });
    }

    public function down(): void
    {
        Schema::table('hajj_step_videos', function (Blueprint $table) {
            $table->string('step_key')->nullable()->after('id');
        });

        Schema::table('hajj_step_videos', function (Blueprint $table) {
            $table->dropColumn(['title', 'sort_order']);
        });
    }
};
