<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Update Users Table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('email'); // 'admin' or 'user'
            }
            if (!Schema::hasColumn('users', 'is_banned')) {
                $table->boolean('is_banned')->default(false)->after('role');
            }
            if (!Schema::hasColumn('users', 'plan')) {
                $table->string('plan')->default('free')->after('is_banned');
            }
            if (!Schema::hasColumn('users', 'credits')) {
                $table->integer('credits')->default(50)->after('plan');
            }
        });

        // 2. Create Themes Table
        if (!Schema::hasTable('themes')) {
            Schema::create('themes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('key')->unique(); // e.g., 'luxury-gold'
                $table->boolean('is_active')->default(true);
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });

            // Seed default themes
            DB::table('themes')->insert([
                ['name' => 'Light', 'key' => 'light', 'is_active' => true, 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Dark', 'key' => 'dark', 'is_active' => true, 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Neon Cyber', 'key' => 'neon-cyber', 'is_active' => true, 'is_default' => false, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Luxury Gold', 'key' => 'luxury-gold', 'is_active' => true, 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 3. Create Contact Messages Table
        if (!Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('subject')->nullable();
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_banned', 'plan', 'credits']);
        });
        Schema::dropIfExists('themes');
        Schema::dropIfExists('contact_messages');
    }
};
