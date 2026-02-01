<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tool_runs', function (Blueprint $table) {
            $table->integer('duration_ms')->nullable()->after('tokens_used');
            $table->integer('cost_credits')->nullable()->after('duration_ms');
        });
    }

    public function down(): void
    {
        Schema::table('tool_runs', function (Blueprint $table) {
            $table->dropColumn(['duration_ms', 'cost_credits']);
        });
    }
};
