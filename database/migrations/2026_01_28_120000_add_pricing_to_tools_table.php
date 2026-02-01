<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->integer('cost_credits')->nullable()->after('usage_limit');
            $table->integer('daily_budget_credits')->nullable()->after('cost_credits');
        });
    }

    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn(['cost_credits', 'daily_budget_credits']);
        });
    }
};
