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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('types');
        });
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropConstrainedForeignId('type_id');
        });
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('goods_type')->nullable();
            $table->foreign('goods_type')->references('id')->on('types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('type_id');
        });
        Schema::table('order_details', function (Blueprint $table) {
            $table->foreignId('type_id');
            $table->foreign('type_id')->references('id')->on('types');
        });
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('goods_type');
        });
    }
};
