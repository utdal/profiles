<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('backup:run')->daily()->at('01:00')->when(function() {
            return config('app.enable_backup');
        });
        $schedule->command('backup:clean')->daily()->at('03:00')->when(function() {
            return config('app.enable_backup_clean');
        });
        $schedule->command('backup:monitor')->daily()->at('04:00')->when(function() {
            return config('app.enable_backup_monitor');
        });
        $schedule->command('orcid:update')->withoutOverlapping()->when(function() {
            return config('app.enable_orcid_update');
        });
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
