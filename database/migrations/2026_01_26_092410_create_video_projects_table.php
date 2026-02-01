<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('video_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title')->nullable();
            $table->text('prompt');
            $table->longText('script_content')->nullable(); // The AI generated script

            // Configuration
            $table->string('model_provider')->default('vexub'); // vexub, replicate, kling
            $table->string('visual_style')->default('realistic'); // cinematic, anime, etc
            $table->json('settings')->nullable(); // Extra params like aspect ratio, duration

            // State
            $table->string('status')->default('draft'); // draft, scripting, generating, completed, failed
            $table->string('video_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_projects');
    }
};
