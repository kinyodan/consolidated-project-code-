<?php
namespace App\Http\Controllers\Administration\Queries\Alumni;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\InstitutionAlumnus;
use App\Models\QuestionCategory;
use App\Models\QuestionsResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class GetAlumniQuestionsQueryController
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
     * Get the alumnus questions
     * @param string|null $alumnus_id
     * @return JsonResponse
     */
    public function get(?string $alumnus_id): JsonResponse
    {
        try{
            $alumnus_id = CraydelHelperFunctions::toNumbers($alumnus_id);

            if(empty($alumnus_id)){
                throw new Exception("Missing alumni ID");
            }

            if(!DB::table((new InstitutionAlumnus())->getTable())->where('id', $alumnus_id)->exists()){
                throw new Exception("Invalid alumni ID");
            }

            $questions = QuestionCategory::with(['questions'])->where('is_published', 1)->get();

            $questions = collect($questions)->map(function ($category) use($alumnus_id){
                $questions = $category->questions ?? array();
                return [
                    'id' => $category->id ?? null,
                    'title' => $category->title ?? null,
                    'is_published' => $category->is_published ?? null,
                    'questions' => collect($questions)->map(function ($question) use($alumnus_id){
                        if(!is_null($question)){
                            $alumnus_id = CraydelHelperFunctions::toNumbers($alumnus_id);
                            $current_score = "";

                            if(!empty($alumnus_id)){
                                $current_score = DB::table((new QuestionsResponse())->getTable())
                                    ->where('question_id', $question->id)
                                    ->where('institution_alumni_id', $alumnus_id)
                                    ->value('scores');
                            }

                            return [
                                'id' => $question->id ?? null,
                                'question_category_id' => $question->question_category_id ?? null,
                                'title' => $question->title ?? null,
                                'description' => $question->description ?? null,
                                'is_published' => $question->is_published ?? null,
                                'current_score' => !empty($current_score) ? $current_score : ""
                            ];
                        }else{
                            return null;
                        }
                    })->toArray()
                ];
            });

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.alumni_questions_listed'),
                $questions
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
