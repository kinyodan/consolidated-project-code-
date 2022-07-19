<?php
namespace App\Http\Controllers\Administration\Commands\Alumni;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Models\AcademicDiscipline;
use App\Models\Institution;
use App\Models\InstitutionAlumnus;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator as v;

class UpdateAlumnusProfileCommandController
{
    use CanLog;

    /**
     * @var array $allowed_course_types
    */
    public $allowed_course_types = [
        'Undergraduate',
        'Post-graduate',
        'Diploma',
        'Certificate'
    ];

    /**
     * @var InstitutionController
     */
    protected $institutionController;

    /**
     * Constructor
     * @param InstitutionController $institutionController
     */
    public function __construct(InstitutionController $institutionController)
    {
        $this->institutionController = $institutionController;
    }

    public function updateProfile(Request $request, $alumni_id): JsonResponse
    {
        try {
            $validate = $this->validate($request);

            if(!$validate->status){
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    $validate->status,
                    $validate->message
                )));
            }

            DB::transaction(function () use($request){
                $institution_alumni_id = CraydelHelperFunctions::toNumbers($request->get('institution_alumni_id'));
                $alumni_name = CraydelHelperFunctions::toCleanString($request->get('alumni_name'));
                $email = CraydelHelperFunctions::toEmailAddress($request->get('email'));
                $institution_code = CraydelHelperFunctions::toCleanString($request->get('institution_code'));
                $course_type = CraydelHelperFunctions::toCleanString($request->get('course_type'));
                $course_category = CraydelHelperFunctions::toNumbers($request->get('course_category'));
                $course_taken = CraydelHelperFunctions::toCleanString($request->get('course_taken'));
                $current_organisation = CraydelHelperFunctions::toCleanString($request->get('current_employer'));
                $current_position = CraydelHelperFunctions::toCleanString($request->get('current_position'));
                $current_location = CraydelHelperFunctions::toCleanString($request->get('current_location'));
                $graduation_year = CraydelHelperFunctions::toNumbers($request->get('graduation_year'));

                DB::table((new InstitutionAlumnus())->getTable())
                    ->where('id', $institution_alumni_id)
                    ->update([
                        'institution_code' => $institution_code,
                        'alumni_name' => $alumni_name,
                        'graduation_year' => $graduation_year,
                        'course_taken' => $course_taken,
                        'course_type' => $course_type,
                        'course_category' => $course_category,
                        'current_employer' => $current_organisation,
                        'current_position' => $current_position,
                        'current_location' => $current_location,
                        'email' => $email,
                        'university_name' => DB::table((new Institution())->getTable())
                            ->where('institution_code', $institution_code)
                            ->value('institution_name')
                    ]);
            });

            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('alumni.success.profile')
            )));
        } catch (Exception $exception) {
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('alumni.errors.error_creating_profile')
            )));
        }
    }

    /**
     * Validate
     *
     * @param Request $request
     * @return CraydelInternalResponseHelper
     */
    private function validate(Request $request): CraydelInternalResponseHelper{
        $institution_alumni_id = CraydelHelperFunctions::toNumbers($request->get('institution_alumni_id'));
        $alumni_name = CraydelHelperFunctions::toCleanString($request->get('alumni_name'));
        $email = CraydelHelperFunctions::toEmailAddress($request->get('email'));
        $institution_code = CraydelHelperFunctions::toCleanString($request->get('institution_code'));
        $course_type = CraydelHelperFunctions::toCleanString($request->get('course_type'));
        $course_category = CraydelHelperFunctions::toCleanString($request->get('course_category'));
        $course_taken = CraydelHelperFunctions::toCleanString($request->get('course_taken'));
        $current_organisation = CraydelHelperFunctions::toCleanString($request->get('current_employer'));
        $current_position = CraydelHelperFunctions::toCleanString($request->get('current_position'));
        $current_location = CraydelHelperFunctions::toCleanString($request->get('current_location'));
        $graduation_year = CraydelHelperFunctions::toNumbers($request->get('graduation_year'));

        if(!v::intVal()->notEmpty()->validate($institution_alumni_id)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing alumni ID'
            ));
        }else{
            if(!DB::table((new InstitutionAlumnus())->getTable())->where('id', $institution_alumni_id)->exists()){
                return (new CraydelInternalResponseHelper(
                    false,
                    'Invalid alumni ID'
                ));
            }
        }

        if(!v::stringVal()->notEmpty()->validate($alumni_name)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing alumni name'
            ));
        }

        if(!v::email()->notEmpty()->validate($email)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing alumni email'
            ));
        }else{
            if(DB::table((new InstitutionAlumnus())->getTable())->where('id', '!=', $institution_alumni_id)->where('email', $email)->exists()){
                return (new CraydelInternalResponseHelper(
                    false,
                    'Another alumnus has this email address please try again'
                ));
            }
        }

        if(!v::stringVal()->notEmpty()->validate($institution_code)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing institution code'
            ));
        }else{
            if(!DB::table((new Institution())->getTable())->where('institution_code', $institution_code)->exists()){
                return (new CraydelInternalResponseHelper(
                    false,
                    'Invalid institution code'
                ));
            }
        }

        if(!v::stringVal()->notEmpty()->validate($course_type)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing course type'
            ));
        }else{
            if(!in_array($course_type, $this->allowed_course_types)){
                return (new CraydelInternalResponseHelper(
                    false,
                    'Invalid course type'
                ));
            }
        }

        if(!v::stringVal()->notEmpty()->validate($course_category)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing course category code'
            ));
        }else{
            if(!DB::table((new AcademicDiscipline())->getTable())->where('discipline_code', $course_category)){
                return (new CraydelInternalResponseHelper(
                    false,
                    'Invalid course category code'
                ));
            }
        }

        if(!v::stringVal()->notEmpty()->validate($course_taken)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing or Invalid course taken value'
            ));
        }

        if(!v::optional(v::stringVal())->validate($current_organisation)){
            return (new CraydelInternalResponseHelper(
                false,
                'Invalid current employer value'
            ));
        }

        if(!v::optional(v::stringVal())->validate($current_position)){
            return (new CraydelInternalResponseHelper(
                false,
                'Invalid current position'
            ));
        }

        if(!v::optional(v::stringVal())->validate($current_location)){
            return (new CraydelInternalResponseHelper(
                false,
                'Invalid current location'
            ));
        }

        if(!v::intVal()->min(Carbon::now()->subYears(60)->year)->max(Carbon::now()->year)->notEmpty()->validate($graduation_year)){
            return (new CraydelInternalResponseHelper(
                false,
                'Invalid current location'
            ));
        }

        return (new CraydelInternalResponseHelper(
            true,
            'Validated'
        ));
    }
}
