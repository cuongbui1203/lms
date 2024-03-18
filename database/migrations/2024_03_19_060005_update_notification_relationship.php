<?php

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
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('from_id')->references('id')->on('work_plates');
            $table->foreign('to_id')->references('id')->on('work_plates');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('from_id');
            $table->dropConstrainedForeignId('to_id');
            $table->dropConstrainedForeignId('status_id');
            $table->dropConstrainedForeignId('order_id');
        });
    }
};
