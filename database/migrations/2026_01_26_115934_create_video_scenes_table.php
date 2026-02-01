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
        Schema::create('video_scenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_project_id')->constrained()->cascadeOnDelete();
            $table->integer('sequence_order')->default(1);
            $table->text('script_text')->nullable();
            $table->text('image_prompt');
            $table->string('status')->default('pending'); // pending, generating, completed, failed
            $table->string('video_url')->nullable();
            $table->string('replicate_prediction_id')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_scenes');
    }
};
