<?php

use App\Enums\AddressTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('work_plates', function (Blueprint $table) {
            $table->dropColumn('vung');
        });
        Schema::table('work_plates', function (Blueprint $table) {
            $table->string('vung');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_plates', function (Blueprint $table) {
            $table->dropColumn('vung');
        });
        Schema::table('work_plates', function (Blueprint $table) {
            $table->enum('vung', AddressTypeEnum::getValues());
        });
    }
};
