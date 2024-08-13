<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'role' => 'admin',
            'balance' => 0
        ]);

        User::create([
            'name' => 'Microsoft',
            'email' => 'microsoft@gmail.com',
            'password' => bcrypt('microsoft'),
            'role' => 'instructor',
            'balance' => 0
        ]);

        User::create([
            'name' => 'Denny Caknan',
            'email' => 'denny@gmail.com',
            'password' => bcrypt('denny123'),
            'role' => 'user',
            'balance' => 0
        ]);
    }
}
