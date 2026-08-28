<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_videos', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();   // matches a key in config/appeal-videos.php
            $table->string('title')->nullable();    // optional caption override
            $table->string('video_url', 1000);      // YouTube/Vimeo/Facebook link or an uploaded file path
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_videos');
    }
};
