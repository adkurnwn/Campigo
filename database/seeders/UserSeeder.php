<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['id' => '1','name' => 'Ade Kurniawan','email' => 'adkur1212@gmail.com','email_verified_at' => NULL,'password' => '$2y$12$upDuJwVrh4n9f65LNXVD5OisTqLgdK3yA5sjQmUOTKNbgZrNjICau','remember_token' => '9ay0euKFpYsnwfUcOnbqwD9NUFgXsFfBkm1lyNPt9PaIvKPpFX5hVixGJdSy','created_at' => '2024-10-14 03:32:12','updated_at' => '2024-11-23 18:44:49','statususer' => 'active','role' => 'admin'],
            ['id' => '3','name' => 'yes','email' => 'haha@haha.com','email_verified_at' => NULL,'password' => '$2y$12$31uGVXMCAgWijzztI/CO3uwGUbWJnka9SIq3LlGIy8ekxVajM8KCm','remember_token' => 'ENxIHAhjsA2mHeeSKM6hjYTIFZyHWUHqXWBbkhjKbdjzbG57hMXEU8UWCkN6','created_at' => '2024-10-20 08:43:06','updated_at' => '2024-11-27 18:29:36','statususer' => 'active','role' => 'user'],
        ]);
    }
}
