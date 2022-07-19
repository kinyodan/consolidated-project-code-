<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HelperFunctions;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventsController
{
    use CanLog;

    /**
     * Receive a notification when a user is created
    */
    public function userCreated(Request $request){
        try{
            $email = $request->input('email');

            if(HelperFunctions::isNull($email)){
                throw new Exception("Empty email value when confirming that the account has been created");
            }

            if(!HelperFunctions::isEmail($email)){
                throw new Exception("Invalid email address when confirming that the account has been created : ".$email);
            }

            DB::transaction(function () use($email){
                DB::table((new Student())->getTable())
                    ->where('student_email', HelperFunctions::toEmailAddress($email))
                    ->update([
                        'is_craydel_account_created' => 1,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            });
        }catch (Exception $exception){
            self::logException($exception);
        }
    }

    /**
     * Receive a notification when a user is activated
    */
    public function userActivated(Request $request){
        try{
            $email = $request->input('email');

            if(HelperFunctions::isNull($email)){
                throw new Exception("Empty email value when confirming that the account has been activated.");
            }

            if(!HelperFunctions::isEmail($email)){
                throw new Exception("Invalid email address when confirming that the account has been activated: ".$email);
            }

            DB::transaction(function () use($email){
                DB::table((new Student())->getTable())
                    ->where('student_email', HelperFunctions::toEmailAddress($email))
                    ->update([
                        'is_account_activated' => 1,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            });
        }catch (Exception $exception){
            self::logException($exception);
        }
    }

    /**
     * Receive a notification when the user has finished the assessment
    */
    public function userHasTakenAssessment(Request $request){
        try{
            $email = $request->input('email');
            $student_assessment_code = $request->input('student_code');

            if(HelperFunctions::isNull($email)){
                throw new Exception("Empty email value while updating the student's assessment status");
            }

            if(!HelperFunctions::isEmail($email)){
                throw new Exception("Invalid email address while updating the student's assessment status: ".$email);
            }

            if(HelperFunctions::isNull($student_assessment_code)){
                throw new Exception("Empty student assessment student code while updating the student's assessment status");
            }

            DB::transaction(function () use($email, $student_assessment_code){
                DB::table((new Student())->getTable())
                    ->where('student_email', HelperFunctions::toEmailAddress($email))
                    ->update([
                        'has_done_career_counselling' => 1,
                        'student_assessment_code' => HelperFunctions::toCleanString($student_assessment_code),
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            });
        }catch (Exception $exception){
            self::logException($exception);
        }
    }

    /**
     * Receive a notification when the student created an assessment account
    */
    public function userHasSubscribedToAssessment(Request $request){
        try{
            $email = $request->input('email');

            if(HelperFunctions::isNull($email)){
                throw new Exception("Empty email value while updating the student's assessment subscription");
            }

            if(!HelperFunctions::isEmail($email)){
                throw new Exception("Invalid email address while updating the student's assessment subscription status: ".$email);
            }

            DB::transaction(function () use($email){
                DB::table((new Student())->getTable())
                    ->where('student_email', HelperFunctions::toEmailAddress($email))
                    ->update([
                        'has_subscribed_for_assessment' => 1,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            });
        }catch (Exception $exception){
            self::logException($exception);
        }
    }

    /**
     * Receive a notification when the user has applied for higher learning
    */
    public function userAppliedForHigherLearning(Request $request){
        try{
            self::logMessage("Event dat: ". print_r($request->all(), true));
            $email = $request->input('email');
            $student_opportunity_code = $request->input('opportunity_code');
            $student_opportunity_status = $request->input('opportunity_status');
            $student_opportunity_institution = $request->input('opportunity_institution');
            $student_opportunity_institution_location = $request->input('opportunity_institution_location');
            $student_opportunity_intake = $request->input('opportunity_intake');
            $student_opportunity_course = $request->input('opportunity_course');

            if(HelperFunctions::isNull($email)){
                throw new Exception("Empty email value while updating the student's application status");
            }

            if(!HelperFunctions::isEmail($email)){
                throw new Exception("Invalid email address while updating the student's application status: ".$email);
            }

            if(HelperFunctions::isNull($student_opportunity_code)){
                throw new Exception("Empty student opportunity code while updating the student's application status");
            }

            if(HelperFunctions::isNull($student_opportunity_status)){
                throw new Exception("Empty student opportunity status while updating the student's application status");
            }

            if(HelperFunctions::isNull($student_opportunity_institution)){
                throw new Exception("Empty student opportunity institution while updating the student's application status");
            }

            if(HelperFunctions::isNull($student_opportunity_institution_location)){
                throw new Exception("Empty student opportunity location while updating the student's application status");
            }

            if(HelperFunctions::isNull($student_opportunity_intake)){
                throw new Exception("Empty student opportunity intake while updating the student's application status");
            }

            if(HelperFunctions::isNull($student_opportunity_course)){
                throw new Exception("Empty student opportunity course while updating the student's application status");
            }

            DB::transaction(function () use($request){
                $email = HelperFunctions::toEmailAddress($request->input('email'));
                $student_opportunity_code = HelperFunctions::toCleanString($request->input('opportunity_code'));
                $student_opportunity_status = $request->input('opportunity_status');
                $student_opportunity_institution = $request->input('opportunity_institution');
                $student_opportunity_institution_location = $request->input('opportunity_institution_location');
                $student_opportunity_intake = $request->input('opportunity_intake');
                $student_opportunity_course = $request->input('opportunity_course');

                DB::table((new Student())->getTable())
                    ->where('student_email', $email)
                    ->update([
                        'has_applied_for_course' => 1,
                        'student_opportunity_code' => $student_opportunity_code,
                        'student_opportunity_status' => $student_opportunity_status,
                        'student_opportunity_institution' => $student_opportunity_institution,
                        'student_opportunity_institution_location' => $student_opportunity_institution_location,
                        'student_opportunity_intake' => $student_opportunity_intake,
                        'student_opportunity_course' => $student_opportunity_course,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            });
        }catch (Exception $exception){
            self::logException($exception);
        }
    }
}
