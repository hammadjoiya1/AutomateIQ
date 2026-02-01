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
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('icon')->nullable();
            $table->string('tool_type')->default('generator');
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->text('prompt_template')->nullable();
            $table->json('input_schema')->nullable();
            $table->string('output_format')->default('text');
            $table->integer('usage_limit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
