<?php
namespace App\Http\Controllers\Administration\Commands\Alumni;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Administration\InstitutionController;
use App\Models\Institution;
use App\Models\InstitutionAlumnus;
use App\Models\QuestionCategory;
use App\Models\Questions;
use App\Models\QuestionsResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator as v;

class ReceiveAlumniQuestionResponseCommandController
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
    public function process(Request $request): JsonResponse
    {
        try {
            $validate = $this->validate($request);

            if(!$validate->status){
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    $validate->status,
                    $validate->message
                )));
            }

            $is_successful = false;

            DB::transaction(function () use($request, &$is_successful){
                $institution_alumni_id = CraydelHelperFunctions::toNumbers($request->get('institution_alumni_id'));
                $question_category_id = CraydelHelperFunctions::toNumbers($request->get('question_category_id'));
                $question_id = CraydelHelperFunctions::toNumbers($request->get('question_id'));
                $institution_code = CraydelHelperFunctions::toCleanString($request->get('institution_code'));
                $scores = CraydelHelperFunctions::toNumbers($request->get('scores'));
                $question_step = CraydelHelperFunctions::toCleanString($request->get('question_step'));

                DB::table((new QuestionsResponse())->getTable())
                    ->updateOrInsert([
                        'institution_alumni_id' => $institution_alumni_id,
                        'question_id' => $question_id
                    ], [
                        'question_category_id' => $question_category_id,
                        'question_id' => $question_id,
                        'institution_code' => $institution_code,
                        'institution_alumni_id' => $institution_alumni_id,
                        'scores' => $scores,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);

                DB::table((new InstitutionAlumnus())->getTable())
                    ->where('id', $institution_alumni_id)
                    ->update([
                        'status' => 1,
                        'question_step' => $question_step,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);

                $is_successful = true;
            });

            if(!$is_successful){
                throw new Exception("Error while scoring the question response");
            }

            $institution_alumni_id = CraydelHelperFunctions::toNumbers($request->get('institution_alumni_id'));
            $question_step = CraydelHelperFunctions::toNumbers($request->get('question_step'));
            $_answered_questions = DB::table((new QuestionsResponse())->getTable())
                ->select(['question_id', 'question_category_id'])
                ->where('institution_alumni_id', $institution_alumni_id)
                ->get();

            $answered_questions = collect($_answered_questions)
                ->groupBy('question_category_id')
                ->toArray();

            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                true,
                'Question response submitted',
                (object)[
                    'answered_questions_list' => $answered_questions,
                    'answered_questions_count' => count($_answered_questions),
                    'latest_question_section' => $question_step,
                    'total_questions' => Questions::count('id')
                ]
            )));
        } catch (Exception $exception) {
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('alumni.errors.error_creating_response')
            )));
        }
    }

    /**
     * Validate
     *
     * @param Request $request
     * @return CraydelInternalResponseHelper
     */
    protected function validate(Request $request): CraydelInternalResponseHelper{
        $institution_alumni_id = $request->get('institution_alumni_id');
        $question_category_id = $request->get('question_category_id');
        $institution_code = $request->get('institution_code');
        $question_id = $request->get('question_id');
        $scores = $request->get('scores');
        $question_step = $request->get('question_step');

        if(!v::intVal()->notEmpty()->validate($institution_alumni_id)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing or invalid Alumni ID'
            ));
        }else{
            if(!DB::table((new InstitutionAlumnus())->getTable())->where('id', $institution_alumni_id)->exists()){
                return (new CraydelInternalResponseHelper(
                    false,
                    'Invalid alumni ID'
                ));
            }
        }

        if(!v::intVal()->notEmpty()->validate($question_category_id)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing question category ID'
            ));
        }else{
            if(!DB::table((new QuestionCategory())->getTable())->where('id', $question_category_id)->exists()){
                return (new CraydelInternalResponseHelper(
                    false,
                    'Invalid question category ID'
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

        if(!v::intVal()->notEmpty()->validate($question_id)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing question ID'
            ));
        }else{
            if(!DB::table((new Questions())->getTable())->where('id', $question_id)->exists()){
                return (new CraydelInternalResponseHelper(
                    false,
                    'Invalid question ID'
                ));
            }
        }

        if(!v::intVal()->notEmpty()->validate($scores)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing or invalid question score value'
            ));
        }

        if(!v::min(1)->max(5)->notEmpty()->validate($scores)){
            return (new CraydelInternalResponseHelper(
                false,
                'The question score value has to be between 1 and 5'
            ));
        }

        if(!v::stringVal()->notEmpty()->validate($question_step)){
            return (new CraydelInternalResponseHelper(
                false,
                'Missing or invalid question step'
            ));
        }

        return (new CraydelInternalResponseHelper(
            true,
            'Validated'
        ));
    }
}
