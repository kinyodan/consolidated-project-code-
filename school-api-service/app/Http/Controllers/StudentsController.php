<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\CraydelResponseHelper;
use App\Http\Controllers\Helpers\HelperFunctions;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\SendEmailJob;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentStream;
use App\Transformers\SchoolAdminTransformer;
use App\Transformers\StudentTransformer;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\SchoolTransformer;
use Throwable;

class StudentsController extends Controller
{
    use CanLog;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $fractal;

    public function __construct()
    {
        $this->fractal = new Manager();
    }

    public function getSchoolAdmin($request)
    {
        return SchoolAdmin::where('craydel_user_id', $request->get('user_code'))->first();
    }

    /**
     * GET /students-build
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function build(Request $request): JsonResponse
    {
        //get the school from the school admin
        try {
            $schoolAdmin = $this->getSchoolAdmin($request);
            if (!empty($schoolAdmin)) {
                $classes = StudentClass::where('school_id', $schoolAdmin->school_id)->get();
                $streams = StudentStream::where('school_id', $schoolAdmin->school_id)->get();
                $responseData = ['data' => [
                    'classes' => $classes,
                    'streams' => $streams
                ]];
                $status = true;
                $message = "";
            } else {
                $status = false;
                $message = "No data available!";
                $responseData = ['data' => []];
            }
            return (new CraydelResponseHelper())->craydelSuccessResponse($status, $message, $responseData);
        } catch (Throwable $e) {
            return (new CraydelResponseHelper())->craydelErrorResponse($e);
        }
    }

    /**
     * GET /students
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $schoolAdmin = $this->getSchoolAdmin($request);
            if (!empty($schoolAdmin)) {
                $itemsPerPage = $request->get('itemsPerPage',15);
                $sortBy = $request->get('sortBy');
                $sortDirection = $request->get('sortDirection');
                $paginator = Student::where('school_id', $schoolAdmin->school_id);

                //set the order
                if(!empty($sortBy) && !empty($sortDirection)){
                    $paginator->orderBy($sortBy,$sortDirection);
                }

                //search
                $search = $request->get('search', false);
                $paginator->when($search, function ($q) use ($search){
                    return $q->where(function ($query) use($search){
                        return $query->where('student_name','LIKE',"%".$search."%");
                    });
                });

                $paginator = $paginator->paginate(intval($itemsPerPage));
                $student = $paginator->getCollection();
                $resource = new Collection($student, new StudentTransformer());
                $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
                $responseData = $this->fractal->createData($resource)->toArray();
                $status = true;
                $message = "";
            } else {
                $status = false;
                $message = "No data available!";
                $responseData = ['data' => []];
            }
            return (new CraydelResponseHelper())->craydelSuccessResponse($status, $message, $responseData);
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * Store the student details
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'school_id' => 'required',
                'class_id' => 'required',
                'stream_id' => 'required',
                'student_name' => 'required',
                'student_email' => 'required|email|unique:students,student_email',
                'student_phone' => 'nullable',
                'student_address' => 'nullable',
            ]);

            if ($validator->fails()) {
                $response_array = ['data' => $validator->messages()];
                return (new CraydelResponseHelper())->craydelSuccessResponse(false, "", $response_array);
            }
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }

        try {
            $schoolAdmin = $this->getSchoolAdmin($request);
            $insertData = $validator->validated();

            if(DB::table((new School())->getTable())->where('id', $schoolAdmin->school_id)->value('school_has_to_collect_full') !== 1){
                $insertData['is_invite_sent'] = 1;
            }else{
                $this->_checkIfSchoolHaAvailableLicenses($schoolAdmin->school_id);
            }

            $insertData['school_id'] = $schoolAdmin->school_id;
            $students = Student::create($insertData);

            if (!empty($students)) {
                $status = true;
                $message = "Student has been successfully invited";
                $resource = new Item($students, new StudentTransformer());
                $response_array = $this->fractal->createData($resource)->toArray();

                DB::transaction(function () use($schoolAdmin){
                    DB::table((new School())->getTable())
                        ->where('id', $schoolAdmin->school_id)
                        ->update([
                            'allowed_license_count' => DB::raw('allowed_license_count - 1')
                        ]);
                });
            } else {
                $status = false;
                $message = "Cannot Create student";
                $response_array = ['data' => []];
            }

            return (new CraydelResponseHelper())->craydelSuccessResponse($status, $message, $response_array);
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * GET a single student /students/id
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $student = Student::find($id);
            if (!empty($student)) {
                $status = true;
                $message = "Student successfully retrieved";
                $resource = new Item($student, new StudentTransformer());
                $responseData = $this->fractal->createData($resource)->toArray();
            } else {
                $status = false;
                $message = "Student does not exist";
                $responseData = ['data' => array()];
            }
            return (new CraydelResponseHelper())->craydelSuccessResponse($status, $message, $responseData);
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * resend the student invite
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resendInvite(Request $request): JsonResponse
    {
        try {
            $ids_string = $request->get('ids');
            $ids_array = explode(',', $ids_string);

            if (!empty($ids_array)) {
                DB::transaction(function () use ($ids_array){
                    foreach ($ids_array as $id) {
                        if(!empty($id)){
                            DB::table((new Student())->getTable())
                                ->where('id', $id)
                                ->update([
                                    'is_invite_sent' => 0
                                ]);
                        }
                    }
                });

                $status = true;
                $message = "Student invite successfully sent";
            } else {
                $status = false;
                $message = "Cannot retrieve student details";
            }

            $response_array = ['data' => []];

            return (new CraydelResponseHelper())->craydelSuccessResponse($status, $message, $response_array);
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * trigger email job
     *
     * @param $student
     * @return void
     * @throws Throwable
     */
    public function triggerEmail($student): void
    {
        try{
            $update_data = [
                'is_invite_sent' => 1,
                'updated_at' => Carbon::now()->toDateTimeString()
            ];

            if(DB::table((new School())->getTable())->where('id', $student->school_id)->value('school_has_to_collect_full') !== 1){
                $subject = "Subject: Hey " . $student->student_name . "! Join the Craydel FutureShapers Platform.";

                $body = view('emails.invite_email', [
                    'title' => 'Join the Craydel FutureShapers Platform.',
                    'greetings' => 'Hi, ' . $student->student_name,
                    'school_name' => $student->school->school_name,
                    'verification_button_cta' => 'Take Psychometric Assessment',
                    'verification_url' => env('CRAYDEL_PSYCHOMETRIC_LINK'),
                ])->render();

                dispatch(new SendEmailJob($student->student_name, $student->student_email, $subject, $body))->onQueue('send_invite_email');
            }else{
                (new CreateAFullPaidStudentAssessmentProfileController())->create(
                    new Request([
                        'email_address' => $student->student_email ?? null,
                        'full_names' => $student->student_name ?? null
                    ])
                );

                $update_data = [
                    'is_account_activated' => 1
                ];
            }

            DB::table((new Student())->getTable())
                ->where('id', $student->id)
                ->update($update_data);
        }catch (Exception $exception){
            DB::table((new Student())->getTable())
                ->where('id', $student->id)
                ->update([
                    'is_invite_sent' => 0
                ]);

            self::logException($exception);
        }
    }

    /**
     * Delete the students
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteStudents(Request $request): JsonResponse
    {
        try {
            $ids_string = $request->get('ids');
            $ids_array = explode(',', $ids_string);
            if (!empty($ids_array)) {
                if (Student::destroy($ids_array)) {
                    //TODO:Check number of students deleted
                    $status = true;
                    $message = "Student(s) successfully deleted";
                    $response_array = ['data' => []];
                } else {
                    $status = false;
                    $message = "Could not delete student(s)";
                    $response_array = ['data' => []];
                }
            } else {
                $status = false;
                $message = "Cannot retrieve student details";
                $response_array = ['data' => []];
            }
            return (new CraydelResponseHelper())->craydelSuccessResponse($status, $message, $response_array);
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * Check if the school has licenses
     * @throws Exception
     */
    protected function _checkIfSchoolHaAvailableLicenses(int $school_id)
    {
        if(HelperFunctions::isNull($school_id)){
            throw new Exception("Missing school ID");
        }

        $has_no_licenses = DB::table((new School())->getTable())
            ->where('id', $school_id)
            ->where('school_has_to_collect_full', 1)
            ->where('allowed_license_count', '<=', 0)
            ->exists();

        if($has_no_licenses){
            throw new Exception("The schools does not have available licenses to issue to the students. Please contact Craydel");
        }
    }
}
