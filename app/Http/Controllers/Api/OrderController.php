<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\AddDetailOrderRequest;
use App\Http\Requests\Order\ArrivedPostRequest;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\GetNextPostRequest;
use App\Http\Requests\Order\MoveOrderRequest;
use App\Models\Noti;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Vehicle;
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
        $notification->status_id = StatusEnum::CREATE;
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

    public function getNextPos(GetNextPostRequest $request)
    {
        $orderIds = $request->getOrders();
        $res = [];
        foreach ($orderIds as $orderId) {
            /** @var Order $order */
            $order = Order::find($orderId);
            $workPlate = routingAnother($order);
            if ($workPlate) {
                $workPlate->load('detail', 'type');
            } else {
                $workPlate = 'shipping';
            }

            $res[$orderId] = $workPlate;
            // $res->pus
        }

        return $this->sendSuccess($res, 'gui dia diem diem goi y tiep theo');
    }

    public function MoveToNextPos(MoveOrderRequest $request)
    {
        $inputs = collect(json_decode($request->data));
        $inputs->map(function ($e) {
            $notification = new Noti();
            $notification->from_id = $e->from_id ?? null;
            $notification->to_id = $e->to_id ?? null;
            $notification->from_address_id = $e->from_address_id ?? null;
            $notification->to_address_id = $e->to_address_id ?? null;
            $notification->description = $e->description ?? null;
            $notification->order_id = $e->orderId;
            $notification->status_id = StatusEnum::TO_THE_TRANSACTION_POINT;
            $notification->save();
        });

        return $this->sendSuccess([], 'move to next post ok');
    }

    public function ArrivedPos(ArrivedPostRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $orders = collect(json_decode($request->data));
        $orders->map(function ($orderId) use ($user) {
            $order = Order::find($orderId);
            $noti = $order->notifications->last();
            if (
                $noti->to_id === $user->work_plate->id ||
                $noti->to_address_id === $user->work_plate->address_id
            ) {
                $noti->status_id = $user->work_plate->type_id === config('type.workPlate.transactionPoint') ?
                StatusEnum::AT_TRANSACTION_POINT : StatusEnum::AT_TRANSPORT_POINT;
            } else {
                $noti->to_id = $user->work_plate->id;
                $noti->to_address_id = $user->work_plate->vung;
                $noti->status_id = $user->work_plate->type_id === config('type.workPlate.transactionPoint') ?
                StatusEnum::AT_TRANSACTION_POINT : StatusEnum::AT_TRANSPORT_POINT;
            }

            $noti->save();
        });

        return $this->sendSuccess([], 'success');
    }

    public function ganChoXe(Order $order, Vehicle $vehicle)
    {
        if (($order->mass + $vehicle->payload) <= $vehicle->max_payload) {
            $order->vehicle_id = $vehicle->id;
            return $this->sendSuccess([], 'success');
        }
        return $this->sendError('fail', ['overload']);
    }
}
