<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected $commands = [
        // Register custom Artisan commands here.
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Schedule jobs to run periodically.
        // For example, run background job handler every minute.
        $schedule->command('run:background-job')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        // Load commands from the app/Console/Commands directory.
        $this->load(__DIR__.'/Commands');

        // Load any additional routes for console commands if necessary.
        require base_path('routes/console.php');
    }
}
