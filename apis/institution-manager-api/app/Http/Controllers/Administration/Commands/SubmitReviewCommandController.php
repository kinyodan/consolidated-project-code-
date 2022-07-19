<?php
namespace App\Http\Controllers\Administration\Commands;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Institution;
use App\Models\InstitutionReview;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator as v;

class SubmitReviewCommandController
{
    /**
     * @var InstitutionController
     */
    protected $institutionController;

    /**
     * Constructor
     * @param InstitutionController $institutionController
     */
    public function __construct(InstitutionController $institutionController){
        $this->institutionController = $institutionController;
    }

    /**
     * Validate review submission
     * @param Request $request
     * @return CraydelInternalResponseHelper
     */
    protected function validate(Request $request): CraydelInternalResponseHelper
    {
        try {
            $rating_score = $request->input('rating_score');
            $full_names = $request->input('rated_by');
            $course_taken = $request->input('course_code');
            $graduation_year = $request->input('graduation_year');
            $review = $request->input('review');

            if(!v::floatVal()->notEmpty()->validate(CraydelHelperFunctions::toNumbers($rating_score))){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.review.invalid_missing_rating_score')
                );
            }

            if(!v::stringVal()->notEmpty()->validate(CraydelHelperFunctions::toCleanString($full_names))){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.review.invalid_missing_full_names')
                );
            }

            if(!v::stringVal()->notEmpty()->validate(CraydelHelperFunctions::toCleanString($course_taken))){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.review.invalid_course_taken')
                );
            }

            if(!v::stringVal()->notEmpty()->validate(CraydelHelperFunctions::toCleanString($graduation_year))){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.review.invalid_graduation_year')
                );
            }

            if(!v::stringVal()->notEmpty()->validate(CraydelHelperFunctions::toCleanString($review))){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.review.invalid_reviews')
                );
            }

            return $this->institutionController->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Validated'
            ));
        }catch (\Exception $exception){
            return $this->institutionController->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Review institution
     *
     * @param string $institution_code
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function review(string $institution_code, Request $request): JsonResponse
    {
        try{
            $user = GetLoggedIUserHelper::getUser($request);

            if(empty($institution_code)){
                throw new Exception('Missing institution code.');
            }

            if(!DB::table((new Institution())->getTable())->where('institution_code', trim($institution_code))->exists()){
                throw new Exception('Invalid institution code.');
            }

            $validate = $this->validate($request);

            if(!$validate->status){
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    $validate->status,
                    $validate->message
                ));
            }

            $rating_score = $request->input('rating_score');
            $full_names = $request->input('rated_by');
            $course_taken = $request->input('course_code');
            $graduation_year = $request->input('graduation_year');
            $review = $request->input('review');

            $created = InstitutionReview::updateOrCreate([
                'institution_code' =>  $institution_code,
                'rated_by_email' => isset($user->email) && !empty($user->email) ? trim($user->email) : null
            ],[
                'rated_by' => $full_names,
                'course_taken' => $course_taken,
                'graduation_year' => $graduation_year,
                'rating_score' => call_user_func(function () use($rating_score){
                    if(floatval($rating_score) <= 5){
                        return floatval($rating_score) >= 1 ? floatval($rating_score) : 1;
                    }else{
                        return floatval($rating_score) > 5 ? 5 : floatval($rating_score);
                    }
                }),
                'review' => nl2br($review),
                'is_published' => 1,
                'published_on' => Carbon::now()->toDateTimeString(),
                'number_of_flags' => 0,
                'created_by' => isset($user->user_code) && !empty($user->user_code) ? trim($user->user_code) : null,
                'updated_by' => isset($user->user_code) && !empty($user->user_code) ? trim($user->user_code) : null,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);

            if($created){
                return  $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.review_submitted')
                ));
            }

            throw new Exception(LanguageTranslationHelper::translate('institutions.errors.review.error_while_submitting_review'));
        }catch (Exception $exception){
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                $exception->getMessage()
            ));
        }
    }
}
