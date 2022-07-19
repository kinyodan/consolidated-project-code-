<?php
namespace App\Console\Commands\Support;

use App\Http\Controllers\Providers\Forex\ForexController;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConvertAllCoursesFeesCurrencyCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:convert-all-course-fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all course fees to USD.';

    /**
     * Default currency code
    */
    protected $default_currency_code;

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
            $this->default_currency_code = 'USD';

            $courses = DB::table((new Course())->getTable())
                ->where('is_deleted', 0)
                ->get([
                    'id',
                    'course_code',
                    'currency',
                    'standard_fee_payable',
                    'foreign_student_fee_payable'
                ]);

            $bar = $this->output->createProgressBar(count($courses));
            $bar->start();

            foreach ($courses as $course){
                $this->update($course);

                $bar->advance();
            }

            $bar->finish();
        }catch (Exception $exception){
            $this->error($exception->getMessage());
        }
    }

    /**
     * Update the course currency
     *
     * @param object|null $course
     * @return false|int
     */
    protected function update(?object $course){
        try{
            if(!isset($course->course_code) || empty($course->course_code)){
                $this->error('Invalid or missing course code');
            }

            if(!isset($course->currency) || empty($course->currency)){
                $this->error('Invalid or missing course currency');
            }

            if($this->updateForeignCurrency($course) && $this->updateStandardCurrency($course)){
                $update = false;

                DB::transaction(function () use($course, &$update){
                    $update = DB::table((new Course())->getTable())
                        ->where('course_code', $course->course_code)
                        ->update([
                            'currency' => $this->default_currency_code,
                            'has_updates' => 1,
                            'is_picked_for_indexing' => 0,
                            'time_picked_for_indexing' => null,
                            'time_taken_to_index' => null,
                            'indexing_error' => null
                        ]);
                });

                return $update;
            }

            return false;
        }catch (\Exception $exception){
            $this->info($exception->getMessage());

            return false;
        }
    }

    /**
     * Update standard currency
     *
     * @param object|null $course
     * @return false|int
     */
    protected function updateStandardCurrency(?object $course){
        try{
            if(!isset($course->standard_fee_payable) || empty($course->standard_fee_payable)){
                $this->info("Empty standard fee payable value");
                return false;
            }

            $convert = new ForexController(
                $course->currency,
                $this->default_currency_code,
                $course->standard_fee_payable
            );

            $result = $convert->convert();

            if(!$result->status){
                $this->error($result->message);
            }

            if(!isset($result->data->converted_value) || empty($result->data->converted_value)){
                $this->error("Unable to get forex converted value");
            }

            $updated = false;

            DB::transaction(function () use ($course, $result, &$updated){
                $updated = DB::table((new Course())->getTable())
                    ->where('course_code', $course->course_code)
                    ->update([
                        'standard_fee_payable' => ceil($result->data->converted_value),
                        'standard_fee_payable_usd' => ceil($result->data->converted_value)
                    ]);
            });

            return $updated;
        }catch (\Exception $exception){
            $this->info($exception->getMessage());

            return false;
        }
    }

    /**
     * Update standard currency
     *
     * @param object|null $course
     * @return false|int
     */
    protected function updateForeignCurrency(?object $course){
        try{
            if(!isset($course->foreign_student_fee_payable) || empty($course->foreign_student_fee_payable)){
                $this->info("Empty foreign student fee payable value");
                return true;
            }

            $convert = new ForexController(
                $course->currency,
                $this->default_currency_code,
                $course->foreign_student_fee_payable
            );

            $result = $convert->convert();

            if(!$result->status){
                $this->error($result->message);
            }

            if(!isset($result->data->converted_value) || empty($result->data->converted_value)){
                $this->error("Unable to get forex converted value");
            }

            $updated = false;

            DB::transaction(function () use ($course, $result, &$updated){
                $updated = DB::table((new Course())->getTable())
                    ->where('course_code', $course->course_code)
                    ->update([
                        'foreign_student_fee_payable' => ceil($result->data->converted_value),
                        'foreign_student_fee_payable_usd' => ceil($result->data->converted_value)
                    ]);
            });

            return $updated;
        }catch (\Exception $exception){
            $this->info($exception->getMessage());
            return false;
        }
    }
}
