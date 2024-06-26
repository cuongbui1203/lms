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
                'address_id' => '27298',
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
                'address_id' => '27298',
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
                'address_id' => '27298',
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
                'address_id' => '27298',
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
                'address_id' => '27298',
                'role_id' => RoleEnum::MANAGER,
                'wp_id' => '1',
            ],
        ];
        User::insert($users);
        // $wardIds = DB::connection('sqlite_vn_map')->table('wards')->get('code')->pluck('code');
        // User::factory(10)->create(['role_id' => RoleEnum::USER])->each(function ($user) use ($wardIds) {
        //     $user->address_id = [$wardIds->random(), ''];
        //     $user->save();
        // });
    }
}
