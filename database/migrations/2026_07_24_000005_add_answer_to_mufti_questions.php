<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mufti_questions')) {
            Schema::table('mufti_questions', function (Blueprint $table) {
                if (! Schema::hasColumn('mufti_questions', 'answer')) {
                    $table->text('answer')->nullable()->after('message');
                }
                if (! Schema::hasColumn('mufti_questions', 'answered_at')) {
                    $table->timestamp('answered_at')->nullable()->after('answer');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('mufti_questions')) {
            Schema::table('mufti_questions', function (Blueprint $table) {
                $table->dropColumn(['answer', 'answered_at']);
            });
        }
    }
};
