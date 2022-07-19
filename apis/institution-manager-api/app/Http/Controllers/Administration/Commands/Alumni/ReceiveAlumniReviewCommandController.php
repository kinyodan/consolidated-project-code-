<?php
namespace App\Http\Controllers\Administration\Commands\Alumni;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Administration\InstitutionController;
use App\Models\Institution;
use App\Models\InstitutionAlumnus;
use App\Models\Reviews;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator as v;

class ReceiveAlumniReviewCommandController
{
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

    /**
     * Create a new institution
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function submit(Request $request): JsonResponse
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
                $institution_code = CraydelHelperFunctions::toCleanString($request->get('institution_code'));
                $reviews = CraydelHelperFunctions::toCleanString($request->get('reviews'));
                $is_consented = CraydelHelperFunctions::toNumbers($request->get('is_consented'));
                $show_your_profile = CraydelHelperFunctions::toNumbers($request->get('show_your_profile'));

                DB::table((new Reviews())->getTable())
                    ->updateOrInsert([
                        'institution_alumni_id' => $institution_alumni_id
                    ],[
                        'institution_alumni_id' => $institution_alumni_id,
                        'institution_code' => $institution_code,
                        'customer_consented_to_review_publish' => $is_consented,
                        'customer_consented_to_use_linkedin_photo' => $show_your_profile,
                        'reviews' => $reviews,
                        'created_at' => Carbon::now()->toDateTimeString()
                    ]);

                DB::table((new InstitutionAlumnus())->getTable())
                    ->where('id', $institution_alumni_id)
                    ->update([
                        'is_consented' => empty($is_consented) ? 0 : 1,
                        'show_your_profile' => empty($show_your_profile) ? 0 : 1,
                        'is_finished' => 1,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            });

            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('alumni.success.reviews')
            )));
        } catch (Exception $exception) {
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('alumni.errors.error_creating_reviews')
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
        $institution_code = CraydelHelperFunctions::toCleanString($request->get('institution_code'));
        $reviews = CraydelHelperFunctions::toCleanString($request->get('reviews'));
        $is_consented = CraydelHelperFunctions::toNumbers($request->get('is_consented'));
        $show_your_profile = CraydelHelperFunctions::toNumbers($request->get('show_your_profile'));

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

        if(!v::stringVal()->notEmpty()->validate($reviews)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing or review message'
            ));
        }

        if(!v::optional(v::intVal())->validate($is_consented)){
            return (new CraydelInternalResponseHelper(
                false,
                'Invalid review publishing consent value'
            ));
        }

        if(!v::optional(v::intVal())->validate($show_your_profile)){
            return (new CraydelInternalResponseHelper(
                false,
                'Invalid profile picture publishing consent value'
            ));
        }

        return (new CraydelInternalResponseHelper(
            true,
            'Validated'
        ));
    }
}
