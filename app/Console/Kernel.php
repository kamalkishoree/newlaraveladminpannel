<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
       $schedule->command('inspire')->hourly();
    //    $schedule->command('conversion:create-job')->everyFourHours()->withoutOverlapping();
    //    $schedule->command('conversion:status-job')->everyFourHours()->withoutOverlapping();
    }

    protected $commands = [
        \App\Console\Commands\ModuleMigrateCommand::class,
        // \App\Console\Commands\CreateClickConversionsCommand::class, // Register command
        // \App\Console\Commands\ConversionStatusJob::class, // Register command
    ];

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
