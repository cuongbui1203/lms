<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Statistical\EmployeeRequest;
use App\Http\Requests\Statistical\GetTotalOrderRequest;
use App\Http\Requests\Statistical\GetTotalWPRequest;
use App\Http\Requests\Statistical\RevenueRequest;
use App\Models\User;
use App\Models\WorkPlate;
use Carbon\Carbon;
use DB;

class StatisticalController extends Controller
{
    public function getTotalEmployee(EmployeeRequest $request)
    {
        $query = User::where('role_id', '!=', RoleEnum::USER);
        if (isset($request->role)) {
            $query->where('role_id', '=', $request->role);
        }
        $total = $query->get(DB::raw('count(*) as total'))->first()->total;
        return $this->sendSuccess([
            'total' => $total,
        ], 'Get total employees successful');
    }

    public function getOrder(GetTotalOrderRequest $request)
    {
        $query = DB::table('orders');
        if (isset($request->status)) {
            $query->where('status_id', '=', $request->status);
        } else {
            $query->whereIn('status_id', [
                StatusEnum::FAIL, // that bai
                StatusEnum::RETURN , //tra ve
                StatusEnum::COMPLETE, // hoan thanh
                StatusEnum::R_DELIVERY, // dang van chuyen
                StatusEnum::CREATE, // tao moi
            ]);
        }
        if (isset($request->start_date)) {
            $query->where('updated_at', '>=', (new Carbon($request->start_date))->startOfMonth());
        } else {
            $query->where('updated_at', '>=', now()->startOfMonth());
        }
        if (isset($request->end_date)) {
            $query->where('updated_at', '<=', (new Carbon($request->end_date))->endOfMonth());
        } else {
            $query->where('updated_at', '<=', now()->endOfMonth());
        }
        $total = $query->get([
            DB::raw('count(*) as total'),
        ])->first()->total;
        return $this->sendSuccess([
            'total' => $total,
        ], 'success');
    }

    public function getTotalRevenue(RevenueRequest $request)
    {
        $query = DB::table('orders')->where('status_id', '=', StatusEnum::COMPLETE);
        if (isset($request->start_date)) {
            $query->where('updated_at', '>=', (new Carbon($request->start_date))->startOfDay());
        } else {
            $query->where('updated_at', '>=', now()->startOfDay());
        }
        if (isset($request->end_date)) {
            $query->where('updated_at', '<=', (new Carbon($request->end_date))->endOfDay());
        } else {
            $query->where('updated_at', '<=', now()->endOfDay());
        }

        $total = $query->get(DB::raw('sum(freight) as total'))->first()->total;
        return $this->sendSuccess([
            'total' => $total ?? 0,
        ], 'Send total revenue success');
    }

    public function getTotalWP(GetTotalWPRequest $request)
    {
        $res = WorkPlate::where('type_id', $request->type)->count();

        return $this->sendSuccess([
            'total' => $res,
        ]);
    }

}
