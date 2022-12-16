<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
            'role' => 1,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@email.com',
            'password' => Hash::make('password'),
            'role' => 2,
        ]);

        // generate 3 users

        \App\Models\User::factory(3)->create();
        
    }
}
