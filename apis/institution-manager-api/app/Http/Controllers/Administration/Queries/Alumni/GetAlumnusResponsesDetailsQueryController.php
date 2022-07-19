<?php
namespace App\Http\Controllers\Administration\Queries\Alumni;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\QuestionsResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetAlumnusResponsesDetailsQueryController
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
     * Get alumnus details
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $alumni_id = $request->get('alumni_id');
        try{
            $responses = QuestionsResponse::where('institution_alumni_id', $alumni_id)
                ->get();

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.alumni_shown'),
                (object)[
                    'responses' => $responses
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
