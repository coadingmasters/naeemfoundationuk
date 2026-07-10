<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('community_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('video_url', 1000); // YouTube/Vimeo link or a direct video file path
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_videos');
        Schema::dropIfExists('community_enquiries');
    }
};
