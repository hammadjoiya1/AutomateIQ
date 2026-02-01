<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tool_runs', function (Blueprint $table) {
            $table->string('model_used')->nullable()->after('cost_credits');
            $table->unsignedTinyInteger('retry_count')->default(0)->after('model_used');
        });
    }

    public function down(): void
    {
        Schema::table('tool_runs', function (Blueprint $table) {
            $table->dropColumn(['model_used', 'retry_count']);
        });
    }
};
