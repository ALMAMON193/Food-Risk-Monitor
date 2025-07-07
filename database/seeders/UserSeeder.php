<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //for the admin
        DB::table('users')->insert(
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
                'user_type' => 'admin',
                'is_verified' => true,
                'email_verified_at' => now(),
                'terms_and_conditions' => true,

            ],
        );
        //for the user
        DB::table('users')->insert(
            [
                'name' => 'Jane Doe',
                'email' => 'user@user.com',
                'password' => Hash::make('12345678'),
                'user_type' => 'user',
                'is_verified' => true,
                'email_verified_at' => now(),
                'terms_and_conditions' => true,
            ],
        );
    }
}
