<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $storage = Storage::disk('database');
        if ($storage->fileExists('database.sqlite')) {
            $storage->put('database.sqlite', '');
        } else {
            $storage->append('database.sqlite', '');
        }
        DB::connection('sqlite_vn_map')
            ->unprepared($storage->get('sql/CreateTables_vn_units_SQLite.sql'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Storage::disk('database')->fileExists('database.sqlite')) {
            Storage::disk('database')->put('database.sqlite', '');
        }
        // Storage::disk('database')->delete('database.sqlite');
    }
};
