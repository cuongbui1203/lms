<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::WAIT_F_DELIVERY,
                'description' => 'Đang chờ lấy hàng',
                'name' => 'WaitForDelivery',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::R_DELIVERY,
                'description' => 'Đang vận chuyển',
                'name' => 'AreDelivery',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::DONE,
                'description' => 'Xác nhận hoàn thành 1 chặng hoặc 1 phần',
                'name' => 'Done',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::AT_TRANSPORT_POINT,
                'description' => 'Đến điểm trung chuyển',
                'name' => 'AtTransportPoint',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::LEAVE_TRANSPORT_POINT,
                'description' => 'Rời điểm trung chuyển',
                'name' => 'LeaveTransportPoint',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::TO_THE_TRANSPORT_POINT,
                'description' => 'Chuyển cho điểm trung chuyển',
                'name' => 'ToTheTransportPoint',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::TO_THE_TRANSACTION_POINT,
                'description' => 'Chuyển cho điểm giao dịch',
                'name' => 'ToTheTransactionPoint',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::RETURN ,
                'description' => 'Chuyển về cho người gửi',
                'name' => 'Return',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::CREATE,
                'description' => 'Tạo biên nhận',
                'name' => 'CreateBN',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::COMPLETE,
                'description' => 'Hoàn thành',
                'name' => 'Complete',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::FAIL,
                'description' => 'Thất bại',
                'name' => 'Fail',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::AT_TRANSACTION_POINT,
                'description' => 'Ở điểm giao dịch',
                'name' => 'AtTransactionPoint',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::LEAVE_TRANSACTION_POINT,
                'description' => 'Rời điểm giao dịch',
                'name' => 'LeaveTransactionPoint',
            ]
        );
        DB::table('statuses')->insert(
            [
                'id' => RoleEnum::SHIPPING,
                'description' => 'Shipping',
                'name' => 'SHIPPING',
            ]
        );
    }
}
