<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert(
            [
                'id' => RoleEnum::Admin,
                'name' => 'Admin',
                'desc' => "Admin",
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('roles')->insert(
            [
                'id' => RoleEnum::Employee,
                'name' => 'Employee',
                'desc' => "Employee",
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('roles')->insert(
            [
                'id' => RoleEnum::User,
                'name' => 'User',
                'desc' => "User",
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('roles')->insert(
            [
                'id' => RoleEnum::Driver,
                'name' => 'Driver',
                'desc' => "Driver",
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
