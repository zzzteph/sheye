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
		$schedule->command('push:queue')->withoutOverlapping();
		$schedule->command('wipe:queue')->hourly();
		$schedule->command('scheduler:run daily')->daily();
		$schedule->command('scheduler:run weekly')->weekly();
		$schedule->command('scheduler:run monthly')->monthly();
		$schedule->command('scheduler:run quarterly')->quarterly();
		
		
    }

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
