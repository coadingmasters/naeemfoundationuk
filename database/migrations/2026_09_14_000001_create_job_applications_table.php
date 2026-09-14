<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('region')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('postcode')->nullable();
            $table->text('address')->nullable();
            $table->string('cv_path', 1000)->nullable(); // uploaded CV/resume file
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
