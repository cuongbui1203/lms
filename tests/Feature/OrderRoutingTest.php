<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AddressTestSeeder;
use Database\Seeders\OrderTestSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase;

class OrderRoutingTest extends TestCase
{
    use CreatesApplication, RefreshDatabase;

    private User $userHandler;
    private $users;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(StatusSeeder::class);
        $this->seed(TypeSeeder::class);
        $this->seed(AddressTestSeeder::class);
        $this->seed(OrderTestSeeder::class);
        $this->users = User::all();
        // Sanctum::actingAs($this->userHandler);
    }

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_create_order()
    {
        $orderData = [
            'sender_name' => 'sender',
            'sender_phone' => '123123123',
            'sender_address_id' => '27280',
            'receiver_address_id' => '27280',
            'receiver_name' => 'receiver',
            'receiver_phone' => '0123456789',
            'type_id' => config('type.goods.normal'),
        ];
        $response = $this
            ->actingAs($this->users->first())
            ->post('/api/orders', $orderData);
        $response->assertStatus(201);
    }

    public function test_get_suggestion_next_pos()
    {

    }
}
