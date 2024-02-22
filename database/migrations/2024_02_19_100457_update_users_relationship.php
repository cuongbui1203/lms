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
                $table->foreign('role_id')
                    ->references('id')
                    ->on('roles');
                $table->foreign('wp_id')
                    ->references('id')
                    ->on('work_plates');
                $table->foreign('img_id')
                    ->references('id')
                    ->on('images');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('role_id');
                $table->dropConstrainedForeignId('wp_id');
                $table->dropConstrainedForeignId('img_id');
            }
        );
    }
};
