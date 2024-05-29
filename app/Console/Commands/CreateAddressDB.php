<?php

namespace App\Console\Commands;

use Database\Seeders\AddressSeeder;
use DB;
use Illuminate\Console\Command;
use Storage;

class CreateAddressDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-address';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create address db';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $storage = Storage::disk('database');
        if ($storage->fileExists('database.sqlite')) {
            $storage->put('database.sqlite', '');
        } else {
            $storage->append('database.sqlite', '');
        }

        DB::connection('sqlite_vn_map')
            ->unprepared($storage->get('sql/CreateTables_vn_units_SQLite.sql'));

        $addressSeed = new AddressSeeder();
        $addressSeed->run();
        echo "ok";
    }
}
