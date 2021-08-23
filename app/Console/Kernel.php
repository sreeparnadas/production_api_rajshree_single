<?php

namespace App\Console;

use App\Console\Commands\GenerateResult;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        '\App\Console\Commands\GenerateResult',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
//        $schedule->command('generate:result')->everyTwoMinutes()->timezone('Asia/Kolkata');

        $schedule->command('generate:result')
            ->cron('*/15 9-15 * * * ')
            ->timezone('Asia/Kolkata');

        $schedule->command('generate:result')
            ->cron('*/20 16-21 * * * ')
            ->timezone('Asia/Kolkata');
        $schedule->command('generate:result')->dailyAt('22:00')->timezone('Asia/Kolkata');
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
