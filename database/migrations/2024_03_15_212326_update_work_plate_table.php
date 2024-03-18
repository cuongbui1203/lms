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
            $table->renameColumn('address', 'address_id');
            $table->enum('vung', [AddressTypeEnum::District, AddressTypeEnum::Ward, AddressTypeEnum::Province])->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_plates', function (Blueprint $table) {
            $table->renameColumn('address_id', 'address');
            $table->dropColumn('vung');
        });
    }
};
