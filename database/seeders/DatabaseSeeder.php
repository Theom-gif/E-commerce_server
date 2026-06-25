<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'liheang.heng@student.passerellesnumeriques.org',
            'password' => bcrypt('G_@11302007'),
            'role' => 'admin',
        ]);

        // Prefilled user for BookBase frontend
        User::create([
            'name' => 'Book Collector',
            'email' => 'collector@bookbase.org',
            'password' => bcrypt('parchment77'),
            'role' => 'user',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
