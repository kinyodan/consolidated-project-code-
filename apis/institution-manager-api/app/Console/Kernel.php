<?php
namespace App\Console;

use App\Console\Commands\BulkProcesses\RetryFailedInstitutionImageProcessingCommand;
use App\Console\Commands\BulkProcesses\RetryFailedInstitutionUploadCommand;
use App\Console\Commands\BulkProcesses\SubscribeToEventsCommand;
use App\Console\Commands\Search\BuildInstitutionSearchCommand;
use App\Console\Commands\Search\ResetInstitutionSearchIndexingCommand;
use App\Console\Commands\TestGeneralCommand;
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
        TestGeneralCommand::class,
        BuildInstitutionSearchCommand::class,
        RetryFailedInstitutionUploadCommand::class,
        RetryFailedInstitutionImageProcessingCommand::class,
        ResetInstitutionSearchIndexingCommand::class,
        SubscribeToEventsCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('search:institution:build')
            ->everyFiveMinutes()
            ->appendOutputTo(storage_path('logs/save-to-search-engine.log'))
            ->name('search:institution:build')
            ->withoutOverlapping(10);

        $schedule->command('bulk:retry:institution-image-processing')
            ->everyFiveMinutes()
            ->appendOutputTo(storage_path('logs/retry-failed-institution-image-upload.log'))
            ->name('bulk:retry:institution-image-processing')
            ->withoutOverlapping(10);

        $schedule->command('bulk:retry:institution-upload')
            ->everyTenMinutes()
            ->appendOutputTo(storage_path('logs/retry-failed-institution-upload.log'))
            ->name('bulk:retry:institution-upload')
            ->withoutOverlapping(10);
    }
}
