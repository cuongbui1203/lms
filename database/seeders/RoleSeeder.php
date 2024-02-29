<?php

namespace Database\Seeders;

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
            'id'=>config('roles.admin'),
            'name'=>'Admin',
            'desc'=>"Admin",
            'created_at'=>now(),
            'updated_at'=>now(),
            ]
        );
        DB::table('roles')->insert(
            [
            'id'=>config('roles.employee'),
            'name'=>'Employee',
            'desc'=>"Employee",
            'created_at'=>now(),
            'updated_at'=>now(),
            ]
        );
        DB::table('roles')->insert(
            [
            'id'=>config('roles.user'),
            'name'=>'User',
            'desc'=>"User",
            'created_at'=>now(),
            'updated_at'=>now(),
            ]
        );
    }
}
