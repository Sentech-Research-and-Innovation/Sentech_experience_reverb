<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

ini_set('memory_limit', '512M');

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {


        // $schedule->command('inspire')->hourly();

        // $schedule->command('csv:process')->dailyAt('08:20');

        $schedule->command('csv:process')->hourly()->appendOutputTo(storage_path('logs.text'));
    }

    protected $commands = [
        'App\Console\Commands\ProcessCsvData'
    ];
    /**
     * Register the commands for the application.
     *
     * @return void
     */


    protected function commands()
    {
        // $this->load(__DIR__ . '/Commands');


        require base_path('routes/console.php');
    }
}
