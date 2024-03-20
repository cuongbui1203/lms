<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::WaitFDelivery,
                'description' => 'Đang chờ lấy hàng',
                'name' => 'WaitForDelivery',
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::RDelivery,
                'description' => 'Đang vận chuyển',
                'name' => 'AreDelivery'
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::Done,
                'description' => 'Xác nhận hoàn thành 1 chặng hoặc 1 phần',
                'name' => 'Done'
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::AtTransportPoint,
                'description' => 'Đến điểm trung chuyển',
                'name' => 'AtTransportPoint'
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::LeaveTransportPoint,
                'description' => 'Rời điểm trung chuyển',
                'name' => 'LeaveTransportPoint',
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::ToTheTransportPoint,
                'description' => 'Chuyển cho điểm trung chuyển',
                'name' => 'ToTheTransportPoint',
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::ToTheTransactionPoint,
                'description' => 'Chuyển cho điểm giao dịch',
                'name' => 'ToTheTransactionPoint',
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::Return,
                'description' => 'Chuyển về cho người gửi',
                'name' => 'Return',
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::Create,
                'description' => 'Tạo biên nhận',
                'name' => 'CreateBN',
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::Complete,
                'description' => 'Hoàn thành',
                'name' => 'Complete',
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::Fail,
                'description' => 'Thất bại',
                'name' => 'Fail',
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::AtTransactionPoint,
                'description' => 'Ở điểm giao dịch',
                'name' => 'AtTransactionPoint',
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::LeaveTransactionPoint,
                'description' => 'Rời điểm giao dịch',
                'name' => 'LeaveTransactionPoint',
            ]
        );
        DB::table("statuses")->insert(
            [
                'id' => StatusEnum::Shipping,
                'description' => 'Shipping',
                'name' => 'SHIPPING',
            ]
        );
    }
}
