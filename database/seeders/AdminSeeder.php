<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name'    => 'Ahmed',
            'last_name'     => 'Nasser',
            'username'      => 'ahmednasser',
            'email'         => 'ahmednasser@gmail.com',
            'password'      => bcrypt('password'),
            'status'        => 'active',
            'role'          => 'admin',
            'last_login_at' => now(),
            'last_login_ip' => '127.0.0.1',
            'email_verified_at' => now(),
            'remember_token'    => Str::random(10),

        ]);
    }
}
