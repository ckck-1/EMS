<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Create Organizer Users
        User::create([
            'name' => 'Event Organizer',
            'email' => 'organizer@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ORGANIZER,
        ]);

        User::create([
            'name' => 'Jane Organizer',
            'email' => 'jane.organizer@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ORGANIZER,
        ]);

        // Create Attendee Users
        User::factory(10)->create([
            'role' => User::ROLE_ATTENDEE,
        ]);
    }
}