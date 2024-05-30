<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;

class StartApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start laravel Application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Prepare to start App');

        // Clear Cache
        $this->callArtisanCommand('cache:clear', 'Clear Cache');

        // Clear Config
        $this->callArtisanCommand('config:clear', 'Clear Config');

        // Cache Config
        $this->callArtisanCommand('config:cache', 'Cache Config');

        // Run Queue in a subprocess
        $this->runInBackground('php artisan queue:work', 'Run Queue');

        // Run App in a subprocess
        $this->callArtisanCommand('serve', 'Run App', [
            '--host' => '0.0.0.0',
            '--port' => env('APP_PORT'),
        ]);

        return 0;
    }

    /**
     * Call an Artisan command and log the result.
     *
     * @param string $command
     * @param string $description
     */
    protected function callArtisanCommand($command, $description, $args = [])
    {
        $this->info("Starting $description...");
        $result = $this->call($command, $args);
        if ($result === 0) {
            $this->info("$description completed successfully.");
        } else {
            $this->error("Failed to complete $description.");
        }
    }

    /**
     * Run a command in the background.
     *
     * @param string $command
     * @param string $description
     */
    protected function runInBackground($command, $description)
    {
        $this->info("Starting $description in background...");
        if (stripos(PHP_OS, 'WIN') === 0) {
            // Windows
            pclose(popen("start /B $command", "r"));
        } else {
            // Unix-based
            exec("$command > /dev/null 2>&1 &");
        }
        $this->info("$description is running in the background.");
    }
}
