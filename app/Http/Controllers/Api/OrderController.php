<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\AddDetailOrderRequest;
use App\Http\Requests\Order\ArrivedPostRequest;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\GetListOrderRequest;
use App\Http\Requests\Order\ListOrderIdRequest;
use App\Http\Requests\Order\MoveOrderRequest;
use App\Http\Requests\Order\UpdateShippedOrderRequest;
use App\Http\Requests\Order\UpdateShippingOrderRequest;
use App\Models\Noti;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Vehicle;
use Auth;
use Cache;
use Exception;

class OrderController extends Controller
{
    public function index(GetListOrderRequest $request)
    {
        $orders = Cache::remember(
            'orders_status_' . $request->status,
            now()->addMinutes(10),
            function () use ($request) {
                $pageSize = $request->pageSize ?? config('paginate.wp-list');
                $page = $request->page ?? 1;
                $relations = ['notifications', 'details'];
                $res = Order::query();
                if ($request->status) {
                    $res = $res->where('status_id', '=', $request->status);
                }

                return $res->get()
                    ->each(fn($order) => $order->append('sender_address', 'receiver_address'))
                    ->paginate($pageSize, $page, $relations);
            }
        );

        return $this->sendSuccess($orders, 'success');
    }

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
        $order->status_id = StatusEnum::CREATE;

        $order->save();

        $notification = new Noti();
        $notification->order_id = $order->id;
        $notification->from_id = $user->wp_id;
        $notification->to_id = $user->wp_id;
        $notification->status_id = StatusEnum::CREATE;
        $notification->description = 'create new order';

        $notification->save();

        $order->load(['notifications']);
        $order->append('sender_address', 'receiver_address');

        return $this->sendSuccess($order);
    }

    public function show(string $order)
    {
        $order = Cache::remember('order_' . $order, now()->addMinutes(10), function () use ($order) {
            return Order::findOrFail($order)
                ->load(['notifications', 'details'])
                ->append('sender_address', 'receiver_address');
        });

        return $this->sendSuccess($order, 'get Order Detail success');
    }

    public function destroy(ListOrderIdRequest $request)
    {
        $orderIds = $request->getOrders();
        try {
            Noti::whereIn('order_id', $orderIds)->delete();
            OrderDetail::whereIn('order_id', $orderIds)->delete();
            Order::whereIn('id', $orderIds)->delete();
        } catch (Exception $e) {} //phpcs:ignore

        return $this->sendSuccess([], 'delete success');
    }

    public function addDetail(AddDetailOrderRequest $request, Order $order)
    {
        $detail = new OrderDetail([
            'order_id' => $order->id,
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

    public function getNextPos(ListOrderIdRequest $request)
    {
        $orderIds = $request->getOrders();
        $res = [];
        foreach ($orderIds as $orderId) {
            /** @var Order $order */
            $order = Cache::remember(
                'order_id_' . $orderId,
                now()->addMinutes(10),
                function () use ($orderId) {
                    return Order::find($orderId);
                }
            );
            $workPlate = routingAnother($order);
            if ($workPlate) {
                $workPlate->load('detail', 'type');
            } else {
                $workPlate = 'shipping';
            }

            $res[$orderId] = $workPlate;
        }

        return $this->sendSuccess($res, 'gui dia diem diem goi y tiep theo');
    }

    public function MoveToNextPos(MoveOrderRequest $request)
    {
        $inputs = collect(json_decode($request->data));
        $orderIds = $inputs->pluck('orderId');
        Order::whereIn('id', $orderIds)->update([
            'status_id' => StatusEnum::R_DELIVERY,
        ]);

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
            $order = Cache::remember(
                'order_id_' . $orderId,
                now()->addMinutes(10),
                function () use ($orderId) {
                    return Order::find($orderId);
                }
            );
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
        if ($order->mass + $vehicle->payload <= $vehicle->max_payload) {
            $order->vehicle_id = $vehicle->id;
            $vehicle->payload += $order->mass;

            return $this->sendSuccess([
                'success' => true,
            ], 'success');
        }

        return $this->sendError('fail', ['overload']);
    }

    public function shipping(UpdateShippingOrderRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $orderIds = $request->getOrders();
        Order::whereIn('id', $orderIds)->update([
            'status_id' => StatusEnum::SHIPPING,
        ]);
        foreach ($orderIds as $orderId) {
            $notification = new Noti();
            $notification->from_id = $user->work_plate->id;
            $notification->to_id = $user->work_plate->id;
            $notification->description = 'Shipping';
            $notification->order_id = $orderId;
            $notification->status_id = StatusEnum::SHIPPING;
            $notification->save();
        }

        return $this->sendSuccess([
            'success' => true,
            'message' => 'success',
        ]);
    }

    public function shipped(UpdateShippedOrderRequest $request, Order $order)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        /** @var \App\Models\Noti $noti */
        $noti = $order->notifications->last();
        $noti->status_id = StatusEnum::DONE;

        $notification = new Noti();
        $notification->from_id = $user->work_plate->id;
        $notification->to_id = $user->work_plate->id;
        $notification->from_address_id = $user->work_plate->address_id;
        $notification->to_address_id = $user->work_plate->address_id;
        $notification->description = 'Shipping';
        $notification->order_id = $order->id;
        $notification->status_id = $request->status;
        $notification->save();

        $order->status_id = $request->status;

        return $this->sendSuccess([
            'success' => true,
        ]);
    }
}
