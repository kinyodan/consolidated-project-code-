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
use function FastRoute\TestFixtures\empty_options_cached;

class CreateQuestionCategoryCommandController
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
            $title = $request->get('title');
            if (empty($title)) {
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('alumni.errors.missing_question_title')
                )));
            }
            if (DB::table('question_categories')->where('title', $request->input('title'))->exists()) {
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('alumni.errors.duplicate_missing_question_title')
                )));
            }


            $payload = [
                'title' => $title,
                'created_at' => Carbon::now()->toDateTimeString()
            ];

            $hasInserted = false;

            DB::transaction(function () use ($payload) {
                $hasInserted = DB::table('question_categories')->insert($payload);
            });


            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('alumni.success.category')
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
