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
        Schema::create(
            'order_details', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->foreignId('type_id');
                $table->foreignId('order_id');
                $table->string('name');
                $table->integer('mass');
                $table->string('desc');
                $table->foreignId('image_id');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
