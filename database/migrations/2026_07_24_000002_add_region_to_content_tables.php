<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Tables whose rows belong to a single region (content + submissions). */
    private array $tables = [
        'causes', 'appeals', 'projects', 'products', 'hero_slides', 'hajj_videos',
        'orders', 'donations', 'volunteers', 'hajj_registrations', 'contact_messages',
    ];

    public function up(): void
    {
        foreach ($this->tables as $t) {
            if (Schema::hasTable($t) && ! Schema::hasColumn($t, 'region')) {
                Schema::table($t, function (Blueprint $table) {
                    // Default 'GB' backfills all existing rows to the current UK site.
                    $table->string('region', 3)->default('GB')->index();
                });
            }
        }

        // Slugs must be unique *per region* (US and UK can both have "prayer-mat").
        if (Schema::hasColumn('products', 'slug')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->unique(['region', 'slug']);
            });
        }
        if (Schema::hasColumn('appeals', 'slug')) {
            Schema::table('appeals', function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->unique(['region', 'slug']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'slug')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropUnique(['region', 'slug']);
                $table->unique(['slug']);
            });
        }
        if (Schema::hasColumn('appeals', 'slug')) {
            Schema::table('appeals', function (Blueprint $table) {
                $table->dropUnique(['region', 'slug']);
                $table->unique(['slug']);
            });
        }

        foreach ($this->tables as $t) {
            if (Schema::hasTable($t) && Schema::hasColumn($t, 'region')) {
                Schema::table($t, function (Blueprint $table) {
                    $table->dropIndex(['region']);
                    $table->dropColumn('region');
                });
            }
        }
    }
};
