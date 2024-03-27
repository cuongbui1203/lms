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
            $table->foreignId('from_id')->nullable()->change();
            $table->foreignId('to_id')->nullable()->change();
            $table->string('from_address_id')->nullable()->change();
            $table->string('to_address_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('from_id')->nullable(false)->change();
            $table->foreignId('to_id')->nullable(false)->change();
            $table->string('from_address_id')->nullable(false)->change();
            $table->string('to_address_id')->nullable(false)->change();
        });
    }
};
