<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\AddDetailOrderRequest;
use App\Http\Requests\Order\ArrivedPostRequest;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\GetListOrderRequest;
use App\Http\Requests\Order\LeaveRequest;
use App\Http\Requests\Order\ListOrderIdRequest;
use App\Http\Requests\Order\MoveOrderRequest;
use App\Http\Requests\Order\UpdateShippedOrderRequest;
use App\Http\Requests\Order\UpdateShippingOrderRequest;
use App\Models\Noti;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Vehicle;
use App\Models\WorkPlate;
use Auth;
use Exception;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index(GetListOrderRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $pageSize = $request->pageSize ?? config('paginate.wp-list');
        $page = (int) $request->page ?? 1;
        $relations = ['notifications', 'details'];
        $statuses = json_decode($request->statuses);
        $orders = Order::with($relations)
            ->limit($pageSize);
        if ($page === -1) {
            $orders = $orders->orderBy('id', 'DESC')->get()->reverse()->values();
        } else {
            $orders = $orders->offset(($page - 1) * $pageSize)->get();
        }
        $temp = collect();

        foreach ($statuses as $status) {
            switch ($status) {
                case StatusEnum::AT_TRANSACTION_POINT:
                case StatusEnum::AT_TRANSPORT_POINT:
                case StatusEnum::TO_THE_TRANSACTION_POINT:
                case StatusEnum::TO_THE_TRANSPORT_POINT:
                    $orders->filter(function ($order) use ($user) {
                        $noti = $order->notifications->last();
                        return (
                            in_array($noti->status_id, [
                                StatusEnum::AT_TRANSACTION_POINT,
                                StatusEnum::AT_TRANSPORT_POINT,
                                StatusEnum::TO_THE_TRANSACTION_POINT,
                                StatusEnum::TO_THE_TRANSPORT_POINT,
                            ]) &&
                            (($noti->to_id === $user->wp_id && $user->role_id != RoleEnum::ADMIN) || $user->role_id === RoleEnum::ADMIN)
                        );
                    })->each(function ($e) use (&$temp) {
                        $temp->add($e);
                    });
                    break;
                case StatusEnum::LEAVE_TRANSACTION_POINT:
                case StatusEnum::LEAVE_TRANSPORT_POINT:
                case StatusEnum::CREATE:
                case StatusEnum::RETURN :
                case StatusEnum::DONE:
                case StatusEnum::FAIL:
                case StatusEnum::COMPLETE:
                    $r = $orders->filter(function ($order) use ($user) {
                        $noti = $order->notifications->last();
                        return (
                            in_array($noti->status_id, [
                                StatusEnum::LEAVE_TRANSACTION_POINT,
                                StatusEnum::LEAVE_TRANSPORT_POINT,
                                StatusEnum::CREATE,
                                StatusEnum::RETURN ,
                                StatusEnum::DONE,
                                StatusEnum::FAIL,
                                StatusEnum::COMPLETE,
                            ]) &&
                            (($noti->from_id === $user->wp_id && $user->role_id != RoleEnum::ADMIN) || $user->role_id === RoleEnum::ADMIN)
                        );
                    })->each(function ($e) use (&$temp) {
                        $temp->add($e);
                    });
                    break;
            }
        }
        $temp = $temp->unique();
        $orders = $temp->filter(function ($order) use ($statuses) {
            return in_array($order->current_status->id, $statuses);
        })->values();

        $res = [];
        $res['total'] = $orders->count();
        $res['currentPage'] = $page;
        $res['pageSize'] = $pageSize;
        $res['data'] = $orders;

        return $this->sendSuccess($res, 'success');
    }

    public function store(CreateOrderRequest $request)
    {
        $user = Auth::user();

        $order = new Order();
        $order->sender_name = $request->sender_name;
        $order->sender_phone = $request->sender_phone;
        $order->sender_address_id = [$request->sender_address_id, $request->sender_address];
        $order->receiver_name = $request->receiver_name;
        $order->receiver_phone = $request->receiver_phone;
        $order->receiver_address_id = [$request->receiver_address_id, $request->receiver_address];
        $order->status_id = StatusEnum::CREATE;
        $order->type_id = $request->type_id;
        $order->created_id = $user->id;
        $order->freight = 0;
        $order->save();

        $notification = new Noti();
        $notification->order_id = $order->id;
        $notification->from_id = $user->wp_id;
        $notification->to_id = $user->wp_id;
        $notification->from_address_id = [$request->sender_address_id, $request->sender_address];
        $notification->to_address_id = [$request->sender_address_id, $request->sender_address];
        $notification->address_current_id = $user->work_plate->vung;
        $notification->status_id = StatusEnum::CREATE;
        $notification->description = 'create new order';

        $notification->save();
        //
        $notification = new Noti();
        $notification->order_id = $order->id;
        $notification->from_id = $user->wp_id;
        $notification->to_id = $user->wp_id;
        $notification->from_address_id = [$request->sender_address_id, $request->sender_address];
        $notification->to_address_id = [$request->sender_address_id, $request->sender_address];
        $notification->address_current_id = $user->work_plate->vung;
        $notification->status_id = $user->work_plate->type_id === config('type.workPlate.transactionPoint') ? StatusEnum::AT_TRANSACTION_POINT : StatusEnum::AT_TRANSPORT_POINT;
        // $notification->description = '';

        $notification->save();

        $order->load(['notifications']);
        $order->append('sender_address', 'receiver_address');

        return $this->sendSuccess($order, 'Create order success', Response::HTTP_CREATED);
    }

    public function show(Order $order)
    {
        $order->load(['notifications', 'details'])
            ->append('sender_address', 'receiver_address');

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
        $details = collect($request->data);
        $details->each(function ($e) use ($order) {
            $e = (object) $e;
            $detail = new OrderDetail([
                'order_id' => $order->id,
                'desc' => $e->desc,
                'mass' => $e->mass,
                'name' => $e->name,
            ]);
            $order->freight += $e->freight;
            if (isset($e->img)) {
                $detail->image_link = $e->img;
            }
            $detail->save();
        });
        $order->save();
        $order->fresh();

        return $this->sendSuccess($order, 'add order detail success');
    }

    public function getNextPos(ListOrderIdRequest $request)
    {
        $orderIds = $request->getOrders();
        $res = [];
        foreach ($orderIds as $orderId) {
            /** @var Order $order */
            $order = Order::find($orderId);
            $workPlates = routingAnother($order);
            if ($workPlates) {
                $workPlates->load('detail', 'type');
            } else {
                $workPlates = 'shipping';
            }
            if (is_array($workPlates)) {
                array_merge($res, $workPlates->values());
            } else {
                $res[] = $workPlates;
            }
        }

        $res = collect($res)->collapse()->unique('id');

        return $this->sendSuccess($res, 'send suggestion next position success');
    }

    public function MoveToNextPos(MoveOrderRequest $request)
    {
        $inputs = collect($request->data);
        $orderIds = $inputs->pluck('orderId');
        Order::whereIn('id', $orderIds)->update([
            'status_id' => StatusEnum::R_DELIVERY,
        ]);

        $inputs->map(function ($e) {
            /** @var User $user */
            $user = auth()->user();
            $e = (object) $e;
            if (isset($e->to_id)) {
                $wp = WorkPlate::find($e->to_id);
            }
            if (isset($wp)) {
                $statusId = $wp->type_id === config('type.workPlate.transactionPoint') ? StatusEnum::TO_THE_TRANSACTION_POINT : StatusEnum::TO_THE_TRANSPORT_POINT;
            } else {
                $statusId = StatusEnum::TO_THE_TRANSPORT_POINT;
            }
            $notification = Order::find($e->orderId)->notifications->last();

            $notification->from_id = $user->wp_id;
            $notification->to_id = $e->to_id ?? null;
            $notification->from_address_id = [
                isset($e->from_address_id) ? $e->from_address_id : $user->work_plate->address->wardCode,
                isset($e->from_address) ? $e->from_address : $user->work_plate->address->address,
            ];
            $notification->to_address_id = [$e->to_address_id ?? null, $e->to_address ?? null];
            $notification->description = $e->description ?? null;
            $notification->order_id = $e->orderId;
            $notification->address_current_id = $user->work_plate->vung;
            $notification->status_id = $statusId;
            $notification->save();
        });

        return $this->sendSuccess([], 'move to next post ok');
    }

    public function leave(LeaveRequest $request)
    {
        // dd($request->data);
        collect($request->data)->each(function ($id) {
            /** @var Order $order */
            $order = Order::find($id);
            $noti = $order->notifications->last();
            $noti->status_id = $noti->status_id === StatusEnum::TO_THE_TRANSACTION_POINT ? StatusEnum::LEAVE_TRANSACTION_POINT : StatusEnum::LEAVE_TRANSPORT_POINT;
            $noti->save();
            $order->save();
        });
        return $this->sendSuccess([], 'success');
    }

    public function ArrivedPos(ArrivedPostRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $orders = collect($request->data);
        $orders->map(function ($e) use ($user) {
            $e = (object) $e;
            $order = Order::find($e->id);
            $order->freight += ((isset($e->distance) ? $e->distance : 0) * env('TRANSPOSITIONS_COST'));
            $noti = $order->notifications->last();
            $last = addressNext($order->sender_address->wardCode, $user->work_plate->vung, $order->receiver_address->wardCode);
            // dd($last, $user->work_plate);
            if (
                (!is_null($noti->to_id) && $noti->to_id === $user->work_plate->id) ||
                ((!is_null($noti->to_address)) && $noti->to_address->wardCode === $user->work_plate->address->wardCode)
            ) {
                $noti->status_id = StatusEnum::DONE;
            } else {
                $noti->to_id = $user->work_plate->id;
                $noti->to_address_id = [$user->work_plate->address->wardCode, $user->work_plate->address->address];
                $noti->address_current_id = $user->work_plate->vung;
                $noti->status_id = StatusEnum::DONE;
            }
            $noti->save();

            $notification = new Noti();
            $notification->order_id = $order->id;
            $notification->from_id = $user->wp_id;
            $notification->to_id = $user->wp_id;
            $notification->from_address_id = [$user->work_plate->address->wardCode, $user->work_plate->address->address];
            $notification->to_address_id = [$user->work_plate->address->wardCode, $user->work_plate->address->address];
            $notification->address_current_id = $user->work_plate->vung;
            $notification->status_id = $user->work_plate->type_id === config('type.workPlate.transactionPoint') ? StatusEnum::AT_TRANSACTION_POINT : StatusEnum::AT_TRANSPORT_POINT;
            // $notification->description = 'create new order';
            $notification->save();
            if (!isset($last)) {
                $order->status_id = StatusEnum::WAIT_F_DELIVERY;
            }
            $order->save();
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
            $notification = Order::find($orderId)->notifications->last();
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
        /** @var \App\Models\Noti $noti */
        $noti = $order->notifications->last();
        $noti->status_id = StatusEnum::DONE;
        $noti->description = 'shipped';
        $noti->save();

        $order->status_id = $request->status;
        $order->save();

        return $this->sendSuccess([
            'success' => true,
        ]);
    }
}
