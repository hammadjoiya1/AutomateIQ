<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tool_runs', function (Blueprint $table) {
            $table->integer('cost_cents')->default(0)->after('cost_credits');
            $table->integer('credits_charged')->default(0)->after('cost_cents');
            $table->integer('input_tokens')->default(0)->after('credits_charged');
            $table->integer('output_tokens')->default(0)->after('input_tokens');
        });
    }

    public function down(): void
    {
        Schema::table('tool_runs', function (Blueprint $table) {
            $table->dropColumn(['cost_cents', 'credits_charged', 'input_tokens', 'output_tokens']);
        });
    }
};
