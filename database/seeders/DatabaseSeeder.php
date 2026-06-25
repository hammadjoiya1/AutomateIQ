<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@faceless.ai',
            'role' => 'admin',
            'plan' => 'pro',
            'theme' => 'midnight-purple',
            'password' => bcrypt('password'),
        ]);

        // Demo User
        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'user@faceless.ai',
            'role' => 'user',
            'plan' => 'free',
            'theme' => 'ocean-breeze',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            ToolSeeder::class,
            NichePackSeeder::class,
        ]);
    }
}
