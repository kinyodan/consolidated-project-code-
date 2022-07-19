<?php

namespace App\Http\Controllers\Administration\Commands;

use App\Events\InstitutionCreatedEvent;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Administration\InstitutionController;
use App\Models\Institution;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Respect\Validation\Validator as v;

class CreateQuestionCommandController
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
    public function create(Request $request): JsonResponse
    {
        try {
            $question_category_id = $request->get('question_category_id');
            $title = $request->get('title');
            $description = $request->get('description');
            $order = $request->get('order');
            if (empty($question_category_id)) {
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('alumni.errors.missing_question_category_id')
                )));
            }
            if (empty($title)) {
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('alumni.errors.missing_question_title')
                )));
            }
            if (empty($description)) {
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('alumni.errors.missing_description')
                )));
            }
            if (empty($order)) {
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('alumni.errors.missing_order')
                )));
            }
            if (DB::table('questions')->where('title', $request->input('title'))->exists()) {
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('alumni.errors.duplicate_missing_question_title')
                )));
            }
            if (DB::table('questions')->where('description', $request->input('description'))->exists()) {
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('alumni.errors.duplicate_missing_question_description')
                )));
            }


            $payload = [
                'question_category_id' =>$question_category_id,
                'title' => $title,
                'description' => $description,
                'order' =>  $order,
                'created_at' => Carbon::now()->toDateTimeString()
            ];

            $hasInserted = false;

            DB::transaction(function () use ($payload) {
                $hasInserted = DB::table('questions')->insert($payload);
            });

                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('alumni.success.questions')
                )));
        } catch (Exception $exception) {
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('alumni.errors.error_creating_alumni')
            )));
        }
    }


}
