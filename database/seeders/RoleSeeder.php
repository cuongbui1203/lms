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
                'id' => RoleEnum::ADMIN,
                'name' => 'Admin',
                'desc' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('roles')->insert(
            [
                'id' => RoleEnum::EMPLOYEE,
                'name' => 'Employee',
                'desc' => 'Employee',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('roles')->insert(
            [
                'id' => RoleEnum::USER,
                'name' => 'User',
                'desc' => 'User',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('roles')->insert(
            [
                'id' => RoleEnum::DRIVER,
                'name' => 'Driver',
                'desc' => 'Driver',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('roles')->insert(
            [
                'id' => RoleEnum::MANAGER,
                'name' => 'Manager',
                'desc' => 'Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('roles')->insert(
            [
                'id' => RoleEnum::SHIPPER,
                'name' => 'Shipper',
                'desc' => 'Shipper',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
