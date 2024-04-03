<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => fake()->name(),
                'email' => 'admin@admin.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'phone' => fake()->phoneNumber(),
                'dob' => now(),
                'username' => 'admin',
                'address' => fake()->address(),
                'role_id' => RoleEnum::ADMIN,
                'wp_id' => '1',
            ],
            [
                'name' => fake()->name(),
                'email' => 'driver@admin.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'phone' => fake()->phoneNumber(),
                'dob' => now(),
                'username' => 'driver',
                'address' => fake()->address(),
                'role_id' => RoleEnum::DRIVER,
                'wp_id' => '1',
            ],
            [
                'name' => fake()->name(),
                'email' => 'user@admin.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'phone' => fake()->phoneNumber(),
                'dob' => now(),
                'username' => 'user',
                'address' => fake()->address(),
                'role_id' => RoleEnum::USER,
                'wp_id' => '1',
            ],
            [
                'name' => fake()->name(),
                'email' => 'employee@admin.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'phone' => fake()->phoneNumber(),
                'dob' => now(),
                'username' => 'employee',
                'address' => fake()->address(),
                'role_id' => RoleEnum::EMPLOYEE,
                'wp_id' => '1',
            ],
            [
                'name' => fake()->name(),
                'email' => 'manager@admin.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'phone' => fake()->phoneNumber(),
                'dob' => now(),
                'username' => 'manager',
                'address' => fake()->address(),
                'role_id' => RoleEnum::MANAGER,
                'wp_id' => '1',
            ],
        ];
        User::insert($users);
        User::factory(10)->create(['role_id' => RoleEnum::USER]);
    }
}
