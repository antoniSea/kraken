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
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '123456789',
            'city' => 'TestCity',
            'apartment' => '1',
            'consent_personal_data' => true,
            'consent_email' => true,
            'consent_marketing' => false,
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin',
            'password' => bcrypt('1!Qaa2@Wss'),
            'is_admin' => true,
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'phone' => '987654321',
            'city' => 'AdminCity',
            'apartment' => 'A1',
            'consent_personal_data' => true,
            'consent_email' => true,
            'consent_marketing' => false,
        ]);

        \App\Models\Konkurs::firstOrCreate(['name' => 'Konkurs Główny']);
    }
}
