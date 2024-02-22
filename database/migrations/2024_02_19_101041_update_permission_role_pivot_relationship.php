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
            'role_permission', function (Blueprint $table) {
                $table->foreign('role_id')
                    ->references('id')
                    ->on('roles');
                $table->foreign('permission_id')->references('id')->on('permissions');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'role_permission', function (Blueprint $table) {
                $table->dropConstrainedForeignId('role_id');
                $table->dropConstrainedForeignId('permission_id');
            }
        );
    }
};
