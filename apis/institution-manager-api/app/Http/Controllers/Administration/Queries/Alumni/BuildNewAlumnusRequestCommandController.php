<?php
namespace App\Http\Controllers\Administration\Queries\Alumni;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\JobTitle;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuildNewAlumnusRequestCommandController
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
     * Build
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function build(Request $request): JsonResponse
    {
        try{
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.alumnus_built'),
                (object)[
                    'job_titles' => JobTitle::active()->orderBy('job_title')->get()
                ]
            ));
        }catch (Exception $exception){
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
