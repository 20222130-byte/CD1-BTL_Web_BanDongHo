<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('12345678'),
                'full_name' => 'Admin Quản Trị',
                'phone' => '0123456789',
                'address' => 'Hà Nội, Việt Nam',
                'role' => 'admin',
                'created_at' => now()
            ],
            [
                'username' => 'customer1',
                'email' => 'customer1@example.com',
                'password' => bcrypt('12345678'),
                'full_name' => 'Nguyễn Văn A',
                'phone' => '0987654321',
                'address' => 'Sài Gòn, Việt Nam',
                'role' => 'customer',
                'created_at' => now()
            ]
        ]);
    }
}
