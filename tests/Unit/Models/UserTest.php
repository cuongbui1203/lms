<?php

namespace Tests\Unit\Models;

use App\Models\Image;
use App\Models\Role;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkPlate;
use Database\Seeders\AddressTestSeeder;
use Tests\Unit\ModelTestCase;

class UserTest extends ModelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AddressTestSeeder::class);
    }

    public function test_user_configuration()
    {
        $this->runConfigurationAssertions(
            new User(),
            [
                'name',
                'email',
                'phone',
                'dob',
                'username',
                'address_id',
                'img_id',
            ],
            [
                'password',
                'remember_token',
                'address_id',
            ],
            [
                'role_id',
                'password',
            ],
            [],
            [
                'email_verified_at' => 'datetime:Y/m/d H:i',
                'password' => 'hashed',
                'dob' => 'date:Y/m/d',
                'id' => 'int',
            ]
        );
    }

    public function test_user_be_long_to_role()
    {
        $user = new User();
        $role = $user->role();
        $this->assertBelongsToRelation($role, $user, new Role(), 'role_id', 'id');
    }

    public function test_user_be_long_to_img()
    {
        $user = new User();
        $img = $user->img();
        $this->assertBelongsToRelation($img, $user, new Image(), 'img_id', 'id');
    }

    public function test_user_be_long_to_work_plate()
    {
        $user = new User();
        $wp = $user->work_plate();
        $this->assertBelongsToRelation($wp, $user, new WorkPlate(), 'wp_id', 'id');
    }

    public function test_user_be_long_to_vehicle()
    {
        $user = new User();
        $vehicle = $user->vehicle();
        $this->assertBelongsToRelation($vehicle, $user, new Vehicle(), 'id', 'driver_id');
    }

    public function test_user_address_assessor()
    {
        $user = new User(
            ['address_id' => ['27298', 'hn']]
        );
        $res = (object) [
            'provinceCode' => '79',
            'districtCode' => '773',
            'wardCode' => '27298',
            'province' => 'Thành phố Hồ Chí Minh',
            'district' => 'Quận 4',
            'ward' => 'Phường 01',
            'address' => 'hn',
        ];
        $this->assertEquals($user->address, $res);
    }

    public function test_user_address_id_mutator()
    {
        $user = new User(
            ['address_id' => ['27298', 'hn']]
        );
        $res = '27298|hn';
        $this->assertEquals($user->address_id, $res);
    }
}
