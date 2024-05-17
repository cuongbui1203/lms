<?php

namespace Tests\Feature;

use App\Models\Order;
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
    use CreatesApplication;
    use RefreshDatabase;

    private User $userHandler;
    private $users;
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(StatusSeeder::class);
        $this->seed(TypeSeeder::class);
        $this->seed(AddressTestSeeder::class);
        $this->seed(OrderTestSeeder::class);
        $this->users = User::all();
        $this->order = Order::where('sender_name', 'senderName')->first();
    }

    public function test_create_order()
    {
        $orderData = [
            'sender_name' => 'sender',
            'sender_phone' => '123123123',
            'sender_address_id' => '27280',
            'receiver_address_id' => '07159',
            'receiver_name' => 'receiver',
            'receiver_phone' => '0123456789',
            'type_id' => config('type.goods.normal'),
            'freight' => 15000,
        ];
        $response = $this
            ->actingAs($this->users->first())
            ->post('/api/orders', $orderData);
        $response->assertStatus(201);
    }

    public function test_get_suggestion_next_pos()
    {
        $user = $this->users->where('email', 'user_1test@test.com')->first();
        $data = json_encode([$this->order->id]);
        $response = $this->actingAs($user)
            ->get('/api/orders/multi/next?orders=' . $data);
        $response->assertStatus(200);
    }

    public function test_get_suggestion_next_pos_false()
    {
        $user = $this->users->where('email', 'user_1test@test.com')->first();
        $data = json_encode([12]);
        $response = $this->actingAs($user)
            ->get('/api/orders/multi/next?orders=' . $data);
        $response->assertStatus(422);
    }

    public function test_move_next_post()
    {
        $user = $this->users->where('email', 'user_1test@test.com')->first();
        $user2 = $this->users->where('email', 'user_2test@test.com')->first();

        $data = [(object)
            [
                'orderId' => $this->order->id,
                'from_id' => (string) $user->wp_id,
                'to_id' => (string) $user2->wp_id,
                'distance' => 15,
            ],
        ];
        $res = $this->actingAs($user)
            ->post('/api/orders/multi/next', [
                'data' => $data,
            ]);
        $res->assertStatus(200);
    }

    public function test_move_next_post_fail()
    {
        $user = $this->users->where('email', 'user_1test@test.com')->first();
        $user2 = $this->users->where('email', 'user_2test@test.com')->first();

        $data = [(object)
            [
                'order_id' => $this->order->id,
                'from_id' => (string) $user->wp_id,
                'to_id' => (string) $user2->wp_id,
            ],
        ];
        $res = $this->actingAs($user)
            ->post('/api/orders/multi/next', [
                'data' => $data,
            ]);
        $res->assertStatus(422);
    }

    public function test_arrived_next_position()
    {
        $user = $this->users->where('email', 'user_2test@test.com')->first();
        $data = [(object) [
            'id' => $this->order->id,
            'distance' => 10,
        ]];
        $res = $this->actingAs($user)
            ->put('/api/orders/multi/arrived', [
                'data' => $data,
            ]);
        $res->assertStatus(200);
    }

    public function test_arrived_next_position_fail()
    {
        $user = $this->users->where('email', 'user_2test@test.com')->first();
        $data = [(object) [
            'id' => $this->order->id + 123,

        ]];
        $res = $this->actingAs($user)
            ->put('/api/orders/multi/arrived', [
                'data' => $data,
            ]);
        $res->assertStatus(422);
    }

    public function test_complete_order_routing()
    {
        $user1 = $this->users->where('email', 'user_1test@test.com')->first();
        $orderData = [
            'sender_name' => 'senderTestRouting',
            'sender_phone' => '123123123',
            'sender_address_id' => '27280',
            'receiver_address_id' => '07159',
            'receiver_name' => 'receiver',
            'receiver_phone' => '0123456789',
            'type_id' => config('type.goods.normal'),
            'freight' => 15000,
        ];
        $res = $this->actingAs($user1)->post('/api/orders', $orderData);
        $res->assertStatus(201);

        $order = Order::where('sender_name', 'senderTestRouting')->first();

        $user2 = $this->users->where('email', 'user_2test@test.com')->first();
        $data = [(object)
            [
                'orderId' => $this->order->id,
                'from_id' => (string) $user1->wp_id,
                'to_id' => (string) $user2->wp_id,
                'distance' => 10,
            ],
        ];
        $res = $this->actingAs($user1)
            ->post('/api/orders/multi/next', [
                'data' => $data,
            ]);
        $res->assertStatus(200);
    }
}
