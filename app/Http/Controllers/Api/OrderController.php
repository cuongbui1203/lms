<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Models\Noti;
use App\Models\Order;
use Auth;

class OrderController extends Controller
{
    public function store(CreateOrderRequest $request)
    {
        $user = Auth::user();

        $order = new Order();
        $order->sender_name = $request->sender_name;
        $order->sender_phone = $request->sender_phone;
        $order->sender_address_id = $request->sender_address_id;
        $order->receiver_name = $request->receiver_name;
        $order->receiver_phone = $request->receiver_phone;

        $order->save();

        $notification = new Noti();
        $notification->order_id = $order->id;
        $notification->from_id = $user->wp_id;
        $notification->to_id = $user->wp_id;
        $notification->status_id = StatusEnum::Create;

        $notification->save();

        $order->load(['notifications']);

        return $this->sendSuccess($order);
    }

    public function show(Order $order)
    {
        $order->load(['notifications', 'details']);

        return $this->sendSuccess($order, 'get Order Detail success');
    }

    public function destroy(Order $order)
    {
        $order->details()->delete();
        $order->notifications()->delete();

        $order->delete();
        return $this->sendSuccess([], 'delete success');
    }
}
