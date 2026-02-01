<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usage_dailies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->integer('tool_runs')->default(0);
            $table->integer('video_generations')->default(0);
            $table->integer('workflow_runs')->default(0);
            $table->integer('overage_tool_runs')->default(0);
            $table->integer('overage_video_generations')->default(0);
            $table->integer('estimated_cost_cents')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_dailies');
    }
};
