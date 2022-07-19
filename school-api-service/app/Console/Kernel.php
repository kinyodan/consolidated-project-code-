<?php
namespace App\Console;

use App\Console\Commands\CreateSchoolAdminCommand;
use App\Console\Commands\CreateSchoolCommand;
use App\Console\Commands\SendUnsentInviteCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreateSchoolCommand::class,
        CreateSchoolAdminCommand::class,
        SendUnsentInviteCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(SendUnsentInviteCommand::class)->everyMinute();
    }
}
