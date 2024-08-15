<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    // protected $commands = [
    //     SendContinueBookingEmail::class,
    // ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->call(function () {
        //     info("called every minute");
        // })->everyMinute();

    //    $schedule->command('email:send-continue-booking')->everyMinute()->timezone('Europe/Helsinki');
        $schedule->command('email:send-continue-booking')->dailyAt('06:00')->timezone('Europe/Helsinki');

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
