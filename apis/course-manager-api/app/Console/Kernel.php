<?php

namespace App\Console;

use App\Console\Commands\BulkProcesses\BulkDeleteCourse;
use App\Console\Commands\BulkProcesses\BulkPublishCourse;
use App\Console\Commands\BulkProcesses\BulkUnpublishCourse;
use App\Console\Commands\BulkProcesses\CreateCoursesFooterCommand;
use App\Console\Commands\BulkProcesses\GenerateCourseAcademicCategoryLinkageFromExistingCoursesCommand;
use App\Console\Commands\BulkProcesses\GenerateFirstYearCourseFeePayableToUSDCommand;
use App\Console\Commands\BulkProcesses\ForcePushFailedLeadsCommand;
use App\Console\Commands\BulkProcesses\ProcessOpportunityStageUpdateCommand;
use App\Console\Commands\BulkProcesses\ProcessStagedLeadsCommand;
use App\Console\Commands\BulkProcesses\ProcessStagedLeadsOneByOneCommand;
use App\Console\Commands\BulkProcesses\PushKeyPhrasesToSearchEngine;
use App\Console\Commands\BulkProcesses\RetryFailedCoursesUploadCommand;
use App\Console\Commands\BulkProcesses\UpdateCourseStandardFeePayableToUSDCommand;
use App\Console\Commands\BulkProcesses\ProcessCourseOverviewToKeyPhrases;
use App\Console\Commands\Search\BuildCoursesForInternalSearchCommand;
use App\Console\Commands\Search\BuildCoursesSearchCommand;
use App\Console\Commands\Search\ClearCoursesIndex;
use App\Console\Commands\Search\GenerateSearchIndexListCommand;
use App\Console\Commands\Search\ResetCourseSearchIndexingRetryCommand;
use App\Console\Commands\Search\UnpublishPublishedCoursesCommand;
use App\Console\Commands\Support\ConvertAllCoursesFeesCurrencyCommand;
use App\Console\Commands\Support\CreateCourseBulkImportTemplateCommand;
use App\Console\Commands\Support\GenerateEnrollmentDetails;
use App\Console\Commands\Support\GeneratePopularityIndexCommand;
use App\Console\Commands\Support\PopulateCourseLeadAndEnrollmentStaticsFromPreviousLeadsDataCommand;
use App\Console\Commands\Support\ReindexCoursesCommand;
use App\Console\Commands\Support\UpdateCourseDisciplineCommand;
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
    BuildCoursesSearchCommand::class,
    BuildCoursesForInternalSearchCommand::class,
    RetryFailedCoursesUploadCommand::class,
    ProcessStagedLeadsCommand::class,
    ResetCourseSearchIndexingRetryCommand::class,
    UpdateCourseStandardFeePayableToUSDCommand::class,
    UpdateCourseDisciplineCommand::class,
    CreateCourseBulkImportTemplateCommand::class,
    CreateCoursesFooterCommand::class,
    GeneratePopularityIndexCommand::class,
    UnpublishPublishedCoursesCommand::class,
    ConvertAllCoursesFeesCurrencyCommand::class,
    ReindexCoursesCommand::class,
    GenerateFirstYearCourseFeePayableToUSDCommand::class,
    ProcessStagedLeadsOneByOneCommand::class,
    ProcessOpportunityStageUpdateCommand::class,
    PopulateCourseLeadAndEnrollmentStaticsFromPreviousLeadsDataCommand::class,
    ForcePushFailedLeadsCommand::class,
    GenerateSearchIndexListCommand::class,
    GenerateCourseAcademicCategoryLinkageFromExistingCoursesCommand::class,
    ClearCoursesIndex::class,
    BulkDeleteCourse::class,
    BulkPublishCourse::class,
    BulkUnpublishCourse::class,
    ProcessCourseOverviewToKeyPhrases::class,
    GenerateEnrollmentDetails::class,
    PushKeyPhrasesToSearchEngine::class
  ];
  
  /**
   * Define the application's command schedule.
   *
   * @param Schedule $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    $schedule->command(ProcessOpportunityStageUpdateCommand::class)
      ->everyTenMinutes()
      ->appendOutputTo(storage_path('logs/process-opportunity-stages-updates.log'))
      ->name('bulk:opportunities:process-staged-opportunity-stages')
      ->withoutOverlapping(10);
    
    $schedule->command(RetryFailedCoursesUploadCommand::class)
      ->everyTenMinutes()
      ->appendOutputTo(storage_path('logs/process-uploaded-courses.log'))
      ->name('bulk:retry:courses-upload')
      ->withoutOverlapping(10);
    
    $schedule->command(BuildCoursesSearchCommand::class)
      ->everyFiveMinutes()
      ->appendOutputTo(storage_path('logs/save-to-search-engine.log'))
      ->name('search:course:build')
      ->withoutOverlapping(10);
    $schedule->command(BuildCoursesForInternalSearchCommand::class)
      ->everyMinute()
      ->appendOutputTo(storage_path('logs/save-to-search-engine.log'))
      ->name('search:course:internal_build')
      ->withoutOverlapping(10);
    
    $schedule->command(GenerateSearchIndexListCommand::class)
      ->everyMinute()
      ->appendOutputTo(storage_path('logs/generate-course-index-list.log'))
      ->name('search:course:generate-index-list')
      ->withoutOverlapping(10);
    
    $schedule->command(UnpublishPublishedCoursesCommand::class)
      ->everyFiveMinutes()
      ->appendOutputTo(storage_path('logs/remove_course_from_search_engine.log'))
      ->name('search:course:unpublish')
      ->withoutOverlapping(10);
    
    $schedule->command(ProcessStagedLeadsCommand::class)
      ->everyTenMinutes()
      ->appendOutputTo(storage_path('logs/push-leads-to-lms-provider.log'))
      ->name('bulk:leads:push-to-lms-provider')
      ->withoutOverlapping(10);
    
    $schedule->command(ForcePushFailedLeadsCommand::class)
      ->everyFiveMinutes()
      ->appendOutputTo(storage_path('logs/push-leads-to-lms-provider.log'))
      ->name('bulk:leads:push-to-lms-provider')
      ->withoutOverlapping(10);
    
    $schedule->command(ProcessStagedLeadsOneByOneCommand::class)
      ->everyFifteenMinutes()
      ->appendOutputTo(storage_path('logs/push-leads-to-lms-provider.log'))
      ->name('bulk:leads:push-to-lms-provider-one-by-one')
      ->withoutOverlapping(10);
    
    $schedule->command(UpdateCourseStandardFeePayableToUSDCommand::class)
      ->dailyAt('01:30')
      ->monthly()
      ->appendOutputTo(storage_path('logs/update-courses-usd-value.log'))
      ->name('bulk:courses:update-standard-fee-payable-to-usd')
      ->withoutOverlapping(10);
    
    $schedule->command(CreateCoursesFooterCommand::class)
      ->dailyAt('01:30')
      ->appendOutputTo(storage_path('logs/footer-cache-update.log'))
      ->name('bulk:courses:create-footer')
      ->withoutOverlapping(10);
    
    $schedule->command(GeneratePopularityIndexCommand::class)
      ->dailyAt('01:00')
      ->appendOutputTo(storage_path('logs/generate-popularity-index.log'))
      ->name('support:generate-popularity-index')
      ->withoutOverlapping(10);
    
    $schedule->command(BulkPublishCourse::class)
      ->everyTenMinutes()
      ->appendOutputTo(storage_path('logs/bulk-publish-course.log'))
      ->name('bulk:publish-courses')
      ->withoutOverlapping(10);
    
    $schedule->command(BulkDeleteCourse::class)
      ->everyTenMinutes()
      ->appendOutputTo(storage_path('logs/bulk-delete-course.log'))
      ->name('bulk:delete-courses')
      ->withoutOverlapping(10);
    
    $schedule->command(BulkUnpublishCourse::class)
      ->everyTenMinutes()
      ->appendOutputTo(storage_path('logs/bulk-unpublish-course.log'))
      ->name('bulk:unpublish-courses')
      ->withoutOverlapping(10);
    $schedule->command(ProcessCourseOverviewToKeyPhrases::class)
      ->everyMinute()
      ->appendOutputTo(storage_path('logs/process-course-overview-to-key-phrases.log'))
      ->name('bulk:process-course-overview-to-key-phrases')
      ->withoutOverlapping(10);
    $schedule->command(GenerateEnrollmentDetails::class)
      ->everyMinute()
      ->appendOutputTo(storage_path('logs/generate-enrollment-details.log'))
      ->name('support:generate-enrollment-details')
      ->withoutOverlapping(10);
    $schedule->command(PushKeyPhrasesToSearchEngine::class)
      ->everyMinute()
      ->appendOutputTo(storage_path('logs/push-key-phrases-to-search-engine.log'))
      ->name('bulk:push-key-phrases-to-search-engine')
      ->withoutOverlapping(10);
  }
}
