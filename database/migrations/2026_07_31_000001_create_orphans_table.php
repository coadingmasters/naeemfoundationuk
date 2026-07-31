<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orphans', function (Blueprint $table) {
            $table->id();
            $table->string('region', 3)->default('GB')->index();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('location')->nullable();
            $table->string('grade')->nullable();
            $table->string('dob')->nullable();          // free text, e.g. "12 March 2010"
            $table->text('story')->nullable();          // short bio shown on the detail page
            $table->string('photo')->nullable();        // web-root relative, e.g. images/orphans/orphan-...jpg
            $table->unsignedInteger('monthly_amount')->nullable(); // suggested monthly sponsorship
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Slugs are unique within a region (matches the Appeal convention).
            $table->unique(['region', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orphans');
    }
};
