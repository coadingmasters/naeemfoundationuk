<?php

use App\Models\Appeal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appeals', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Backfill unique slugs for existing appeals (from their titles).
        $used = [];
        Appeal::query()->orderBy('id')->get()->each(function (Appeal $appeal) use (&$used) {
            $base = Str::slug($appeal->title) ?: 'appeal';
            $slug = $base;
            $n = 2;
            while (in_array($slug, $used, true)) {
                $slug = $base.'-'.$n++;
            }
            $used[] = $slug;
            $appeal->slug = $slug;
            $appeal->saveQuietly();
        });

        Schema::table('appeals', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('appeals', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
