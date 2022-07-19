<?php
namespace App\Console\Commands\Support;

use App\Http\Controllers\Courses\Commands\ImportCourseCommandController;
use App\Http\Controllers\Courses\Commands\ValidateCourseDetails;
use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\CourseTypesHelper;
use App\Http\Controllers\Helpers\CreateBulkCourseUploadTemplateExcelFileHelper;
use App\Http\Controllers\Helpers\InstitutionsHelper;
use App\Http\Controllers\Helpers\LearningModeHelper;
use App\Http\Controllers\Traits\CanLog;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class CreateCourseBulkImportTemplateCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:create-bulk-import-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create bulk import template';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        try{
            $template = new CreateBulkCourseUploadTemplateExcelFileHelper('Bulk_Import_Courses');
            $template->headers(ImportCourseCommandController::$course_template_headers)
                ->setColumnDataList('Course Type', call_user_func(function (){
                    $course_types = CourseTypesHelper::types();
                    $_course_types = [];

                    if(count($course_types) > 0){
                        foreach ($course_types as $type){
                            if(isset($type->name) && !empty($type->name) && !in_array($type->name, $_course_types)){
                                array_push($_course_types, $type->name);
                            }
                        }
                    }

                    return $_course_types;
                }))
                ->setColumnDataList('Academic Discipline', call_user_func(function (){
                    $academic_discipline = AcademicDisciplineHelper::disciplines();
                    $_academic_discipline = [];

                    if(count($academic_discipline) > 0){
                        foreach ($academic_discipline as $discipline){
                            if(isset($discipline->discipline_name) && !empty($discipline->discipline_name) && !in_array($discipline->discipline_name, $_academic_discipline)){
                                array_push($_academic_discipline, $discipline->discipline_name);
                            }
                        }
                    }

                    return $_academic_discipline;
                }))
                ->setColumnDataList('Graduate Level', ValidateCourseDetails::getGraduateLevels())
                ->setColumnDataList('Attendance Type', ValidateCourseDetails::getAttendanceType())
                ->setColumnDataList('Learning Mode', call_user_func(function (){
                    $learning_modes = LearningModeHelper::modes();
                    $_learning_modes = [];

                    if(count($learning_modes) > 0){
                        foreach ($learning_modes as $learning_mode){
                            if(isset($learning_mode->name) && !empty($learning_mode->name) && !in_array($learning_mode->name, $_learning_modes)){
                                array_push($_learning_modes, $learning_mode->name);
                            }
                        }
                    }

                    return $_learning_modes;
                }))
                ->setColumnDataList('Duration Category', ValidateCourseDetails::getCourseDurationCategory())
                ->setColumnDataList('Standard Fee Billed Per', ValidateCourseDetails::getStandardFeeBillingType())
                ->setColumnDataList('Standard Fee Currency', call_user_func(function (){
                    $currencies = InstitutionsHelper::currencies();
                    $_currencies = [];

                    if (isset($currencies->data) && count($currencies->data) > 0){
                        foreach ($currencies->data as $currency){
                            if(isset($currency->currency_code) && !empty($currency->currency_code) && !in_array($currency->currency_code, $_currencies)){
                                array_push($_currencies, $currency->currency_code);
                            }
                        }
                    }

                    return $_currencies;
                }))
                ->forceCellToBeDecimal(
                    'Standard Fee Payable',
                    DataValidation::TYPE_DECIMAL,
                    ValidateCourseDetails::$minimum_course_fees_allowed,
                    ValidateCourseDetails::$maximum_course_fees_allowed
                )
                ->forceCellToBeDecimal(
                    'Foreign Student Fee',
                    DataValidation::TYPE_DECIMAL,
                    0,
                    ValidateCourseDetails::$maximum_course_fees_allowed
                )
                ->forceCellToBeDecimal(
                    'Duration',
                    DataValidation::TYPE_DECIMAL,
                    ValidateCourseDetails::$minimum_course_duration_allowed,
                    ValidateCourseDetails::$maximum_course_duration_allowed
                )
                ->setCellWidth('Course Description', 500)
                ->autoFit()
                ->create();
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
            $this->logException($exception);
        }
    }
}
