<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('subscription_credits')->default(0)->after('credits');
            $table->integer('topup_credits')->default(0)->after('subscription_credits');
            $table->timestamp('last_credit_grant_at')->nullable()->after('topup_credits');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['subscription_credits', 'topup_credits', 'last_credit_grant_at']);
        });
    }
};
