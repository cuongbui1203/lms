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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('img_id')->nullable()->change();
            $table->foreignId('wp_id')->nullable()->change();
            $table->foreignId('email')->nullable()->change();
            $table->foreignId('phone')->nullable()->change();
            $table->foreignId('address')->nullable()->change();
            $table->date('dob')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
