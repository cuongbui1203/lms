<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\AddDetailOrderRequest;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\MoveOrderRequest;
use App\Models\Noti;
use App\Models\Order;
use App\Models\OrderDetail;
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
        $order->receiver_address_id = $request->receiver_address_id;

        $order->save();

        $notification = new Noti();
        $notification->order_id = $order->id;
        $notification->from_id = $user->wp_id;
        $notification->to_id = $user->wp_id;
        $notification->status_id = RoleEnum::CREATE;
        $notification->description = 'create new order';

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

    public function addDetail(AddDetailOrderRequest $request, Order $order)
    {
        $detail = new OrderDetail([
            'order_id' => $order->id,
            'type_id' => $request->type_id,
            'desc' => $request->desc,
            'mass' => $request->mass,
            'name' => $request->name,
        ]);

        if ($request->img) {
            $detail->image_id = storeImage('order_detail', $request->file('img'));
        }

        $detail->save();

        return $this->sendSuccess($detail, 'add order detail success');
    }

    public function getNextPos(Order $order)
    {
        $workPlate = routingAnother($order);
        if ($workPlate) {
            $workPlate->load('detail');
        } else {
            $workPlate = 'shipping';
        }

        return $this->sendSuccess([
            'nextPos' => $workPlate,
        ]);
    }

    public function MoveToNextPos(MoveOrderRequest $request, Order $order)
    {
        $notification = new Noti($request->only([
            'from_id',
            'to_id',
            'from_address_id',
            'to_address_id',
            'description',
        ]));
        $notification->order_id = $order->id;
        $notification->status_id = RoleEnum::TO_THE_TRANSACTION_POINT;
        $notification->save();

        return $this->sendSuccess([], 'move to next post ok');
    }

    public function ArrivedPos(Order $order)
    {
        /** @var User $user */
        $user = auth()->user();
        $noti = $order->notifications->last();
        if (
            $noti->to_id === $user->work_plate->id ||
            $noti->to_address_id === $user->work_plate->address_id
        ) {
            $noti->status_id = $user->work_plate->type_id === config('type.workPlate.transactionPoint') ?
            RoleEnum::AT_TRANSACTION_POINT : RoleEnum::AT_TRANSPORT_POINT;
        } else {
            $noti->to_id = $user->work_plate->id;
            $noti->to_address_id = $user->work_plate->vung;
            $noti->status_id = $user->work_plate->type_id === config('type.workPlate.transactionPoint') ?
            RoleEnum::AT_TRANSACTION_POINT : RoleEnum::AT_TRANSPORT_POINT;
            // $noti->desc .= ';
        }

        $noti->save();

        return $this->sendSuccess([], 'success');
    }
}
