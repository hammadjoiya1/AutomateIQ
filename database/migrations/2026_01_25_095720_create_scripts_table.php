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
        Schema::create('scripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('topic');
            $table->string('tone')->default('professional'); // casual, professional, energetic, humorous
            $table->string('length')->default('medium'); // short, medium, long
            $table->integer('duration')->nullable(); // estimated duration in seconds
            $table->string('target_audience')->nullable();
            $table->text('key_points')->nullable();
            $table->longText('script_content');
            $table->integer('word_count')->default(0);
            $table->integer('tokens_used')->default(0);
            $table->json('metadata')->nullable(); // for additional params
            $table->timestamps();

            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scripts');
    }
};
