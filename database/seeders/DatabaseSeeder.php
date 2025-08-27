<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@bpsu.edu.ph',
            'role' => 'admin',
            'password' => '$2y$12$G.lYNaocVzENOCSMqXMu9.//vC78tofzObbE9FMEwCN0Vghu29REC',
        ]);
    }
}
