<?php
namespace App\Http\Controllers\DisplaySubjects\Queries;

use App\Http\Controllers\DisplaySubjects\DisplaySubjectsController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\DisplaySubjects;
use App\Models\EducationType;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Integer;

class GetDisciplineSubjectsQueryController
{
    use CanPaginate;

    private DisplaySubjectsController $displaySubjectsController;
    private array $default_education_systems = [12, 13];

    /**
     * Constructor
     * @param DisplaySubjectsController $displaySubjectsController
     */
    public function __construct(DisplaySubjectsController $displaySubjectsController)
    {
        $this->displaySubjectsController = $displaySubjectsController;
    }

    public function get(Request $request, $discipline_id): JsonResponse
    {
        try {
            $country_code = CraydelHelperFunctions::toCleanString($request->input('country_code'));

            if(CraydelHelperFunctions::isNull($country_code)){
                throw new Exception("Missing student country code");
            }

            $country_id = CountryHelper::getIDBasedOnCode($country_code);

            if(CraydelHelperFunctions::isNull($country_id)){
                throw new Exception("Unable to resolve the student's country ID based on the provided country code");
            }

            $education_type_id = CraydelHelperFunctions::toCleanString($request->input('education_type_id'));

            if(empty($education_type_id)){
                $education_type_id = $this->getDefaultEducation($country_id);
            }

            $subjects = DisplaySubjects::where('academic_disciplines_id', '=', $discipline_id)
                ->where('education_type_id', '=', $education_type_id);

            if(!in_array($education_type_id, $this->default_education_systems)){
                $subjects = $subjects->where('country_id', '=', $country_id);
            }

            return $this->displaySubjectsController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('display_subjects.success.listed'),
                $subjects
                    ->select('id','subject_title', 'subject_title_description', 'display_order')
                    ->orderBy('display_order', 'ASC')
                    ->get()
            )));
        } catch (Exception $exception) {
            $this->displaySubjectsController->logException($exception);
            return $this->displaySubjectsController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('display_subjects.errors.list_general_errors')
            )));
        }
    }

    /**
     * Get the default education system in the country
    */
    protected function getDefaultEducation(int $country_id){
        return DB::table((new EducationType())->getTable())
            ->where('country_id', $country_id)
            ->whereNotIn('id', $this->default_education_systems)
            ->value('id');
    }
}
