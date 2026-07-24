<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mufti_questions') && ! Schema::hasColumn('mufti_questions', 'region')) {
            Schema::table('mufti_questions', function (Blueprint $table) {
                $table->string('region', 3)->default('GB')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('mufti_questions') && Schema::hasColumn('mufti_questions', 'region')) {
            Schema::table('mufti_questions', function (Blueprint $table) {
                $table->dropIndex(['region']);
                $table->dropColumn('region');
            });
        }
    }
};
