<?php

namespace App\Console;

use App\Console\Commands\CountAccounts;
use App\Console\Commands\DeleteOldGroups;
use App\Console\Commands\DeleteUsersWithoutGroup;
use App\Console\Commands\InformAboutDeletion;
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
        DeleteUsersWithoutGroup::class,
        CountAccounts::class,
        InformAboutDeletion::class,
        DeleteOldGroups::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('wichtel:count-accounts')->daily();
        $schedule->command('wichtel:delete-users')->daily();
        $schedule->command('wichtel:inform-deletion')->hourly();
        $schedule->command('wichtel:delete-groups')->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
