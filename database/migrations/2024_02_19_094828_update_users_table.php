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
        Schema::table(
            'users', function (Blueprint $table) {
                $table->string('phone', 11);
                $table->date('dob');
                $table->string('username')->unique();
                $table->string('address');
                $table->foreignId('role_id');
                $table->foreignId('wp_id');
                $table->foreignId('img_id');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
