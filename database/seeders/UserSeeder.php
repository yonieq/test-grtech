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
        //
        $data = [
            [
                'name' => 'User',
                'email' => 'user@grtech.com',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@grtech.com',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        ];

        foreach ($data as $user) {
            User::create($user);
        }
    }
}