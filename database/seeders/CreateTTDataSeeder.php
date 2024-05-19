<?php

namespace Database\Seeders;

use App\Enums\AddressTypeEnum;
use App\Enums\RoleEnum;
use DB;
use Illuminate\Database\Seeder;

class CreateTTDataSeeder extends Seeder
{
    protected $users = [];
    protected $wps = [];
    protected function createUsers(int $wp_id, string $name, string $code_name, int $num)
    {

        $this->users[] = [
            'name' => $name . ' Manager No.' . $num,
            'email' => 'manager_' . $code_name . '_' . $num . '@magic_post.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'created_at' => now(),
            'updated_at' => now(),
            'phone' => fake()->phoneNumber(),
            'dob' => fake()->dateTimeBetween(),
            'username' => 'manager_' . $code_name . '_' . $num,
            'role_id' => RoleEnum::MANAGER,
            'wp_id' => $wp_id,
        ];

        $this->users[] = [
            'name' => $name . ' Employee',
            'email' => 'employee_' . $code_name . '_' . $num . '@magic_post.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'created_at' => now(),
            'updated_at' => now(),
            'phone' => fake()->phoneNumber(),
            'dob' => fake()->dateTimeBetween(),
            'username' => 'employee_' . $code_name . '_' . $num,
            'role_id' => RoleEnum::EMPLOYEE,
            'wp_id' => $wp_id,
        ];

        $this->users[] = [
            'name' => $name . ' Shipper',
            'email' => 'shipper_' . $code_name . '_' . $num . '@magic_post.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'created_at' => now(),
            'updated_at' => now(),
            'phone' => fake()->phoneNumber(),
            'dob' => fake()->dateTimeBetween(),
            'username' => 'shipper_' . $code_name . '_' . $num,
            'role_id' => RoleEnum::SHIPPER,
            'wp_id' => $wp_id,
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $initId = 1000;
        $wp = [];
        DB::connection('sqlite_vn_map')->table(DB::raw('wards w'))
            ->join(DB::raw('districts d'), 'w.district_code', '=', 'd.code')
            ->join(DB::raw('provinces p'), 'd.province_code', '=', 'p.code')
        // ->limit(100)
            ->get([
                DB::raw('w.*'),
                DB::raw('d.code_name as district_code_name'),
                DB::raw('d.province_code as province_code'),
                DB::raw('p.code_name as province_code_name'),
                DB::raw('d.name as district_name'),
                DB::raw('p.name as province_name'),
            ])->each(function ($e) use (&$initId, &$wp) {
            $num = fake()->numberBetween(1, 100);
            $wp[] = [ // tt ward
                'id' => $initId,
                'name' => 'Điểm trung chyển ' . $e->name,
                'address_id' => $e->code . '|so ' . $num,
                'type_id' => config('type.workPlate.transshipmentPoint'),
                'cap' => AddressTypeEnum::WARD,
                'vung' => $e->code,
            ];
            $this->createUsers($initId, $e->name, $e->code_name, $initId);
            $initId++;

            $num = fake()->numberBetween(1, 100);
            $wp[] = [ //gd ward
                'id' => $initId,
                'name' => 'Điểm giao dịch ' . $e->name,
                'address_id' => $e->code . '|so ' . $num,
                'type_id' => config('type.workPlate.transactionPoint'),
                'cap' => AddressTypeEnum::WARD,
                'vung' => $e->code,
            ];
            $this->createUsers($initId, $e->name, $e->code_name, $initId);
            $initId++;

            $num = fake()->numberBetween(1, 100);
            $wp[] = [ // tt district
                'id' => $initId,
                'name' => 'Điểm trung chyển ' . $e->district_name,
                'address_id' => $e->code . '|so ' . $num,
                'type_id' => config('type.workPlate.transshipmentPoint'),
                'cap' => AddressTypeEnum::DISTRICT,
                'vung' => $e->district_code,
            ];
            $this->createUsers($initId, $e->district_name, $e->district_code, $initId);
            $initId++;

            $num = fake()->numberBetween(1, 100);
            $wp[] = [ // gd district
                'id' => $initId,
                'name' => 'Điểm giao dịch ' . $e->district_name,
                'address_id' => $e->code . '|so ' . $num,
                'type_id' => config('type.workPlate.transactionPoint'),
                'cap' => AddressTypeEnum::DISTRICT,
                'vung' => $e->district_code,
            ];
            $this->createUsers($initId, $e->district_name, $e->district_code, $initId);
            $initId++;

            $num = fake()->numberBetween(1, 100);
            $wp[] = [ // tt province
                'id' => $initId,
                'name' => 'Điểm trung chyển ' . $e->province_name,
                'address_id' => $e->code . '|so ' . $num,
                'type_id' => config('type.workPlate.transshipmentPoint'),
                'cap' => AddressTypeEnum::PROVINCE,
                'vung' => $e->province_code,
            ];
            $this->createUsers($initId, $e->province_name, $e->province_code, $initId);
            $initId++;

            $num = fake()->numberBetween(1, 100);
            $wp[] = [ // gd province
                'id' => $initId,
                'name' => 'Điểm giao dịch ' . $e->province_name,
                'address_id' => $e->code . '|so ' . $num,
                'type_id' => config('type.workPlate.transactionPoint'),
                'cap' => AddressTypeEnum::PROVINCE,
                'vung' => $e->province_code,
            ];
            $this->createUsers($initId, $e->province_name, $e->province_code, $initId);
            $initId++;
        });
        // dd($wp);
        collect($wp)->chunk(50)->each(function ($e) {
            $arr = [];
            $e->each(function ($ee) use (&$arr) {
                $arr[] = $ee;
            });
            DB::table('work_plates')->insert($arr);
        });
        collect($this->users)->chunk(50)->each(function ($e) {
            $arr = [];
            $e->each(function ($ee) use (&$arr) {
                $arr[] = $ee;
            });
            DB::table('users')->insert($arr);
        });
        // DB::table('work_plates')->insert($wp);
        // DB::table('users')->insert($this->users);
    }
}
