<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\CraydelResponseHelper;
use App\Http\Controllers\Helpers\HelperFunctions;
use App\Models\School;
use App\Models\Student;
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

class SchoolsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private Manager $fractal;

    public function __construct()
    {
        $this->fractal = new Manager();
    }

    /**
     * GET /schools
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $paginator = School::paginate();
            $schools = $paginator->getCollection();
            $resource = new Collection($schools, new SchoolTransformer());
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
            $responseData = $this->fractal->createData($resource)->toArray();

            return (new CraydelResponseHelper())->craydelSuccessResponse(true, "", $responseData);
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * Store the school details
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            if(!HelperFunctions::isEmail($request->input('school_email'))){
                throw new Exception("Invalid school email address");
            }

            if(DB::table((new School())->getTable())->where('school_email', $request->input('school_email'))->exists()){
                throw new Exception("Duplicate school email address is not allowed.");
            }

            if(DB::table((new School())->getTable())->where('school_phone', $request->input('school_phone'))->exists()){
                throw new Exception("Duplicate school phone number is not allowed.");
            }

            if(!HelperFunctions::isURL($request->input('school_logo_url'))){
                throw new Exception("Invalid school logo URL.");
            }

            $validator = Validator::make($request->all(), [
                'school_name' => 'required',
                'school_email' => 'required|email',
                'school_phone' => 'required',
                'school_address' => 'required',
                'school_physical_address' => 'nullable',
                'is_verified' => 'nullable',
                'status' => 'nullable',
                'school_logo_url' => 'required',
                'allowed_license_count' => 'required',
                'school_has_to_collect_full' => 'required',
                'discount_type' => 'nullable',
                'discount_value' => 'nullable',
            ]);

            if ($validator->fails()) {
                $response_array = ['data' => $validator->messages()];
                return (new CraydelResponseHelper())->craydelSuccessResponse(false, "", $response_array);
            }
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }

        try{
            $insertData = $validator->validated();
            $school = School::create($insertData);
            if(!empty($school)){
                $status = true;
                $message = "School successfully created";
                $resource = new Item($school, new SchoolTransformer());
                $response_array =$this->fractal->createData($resource)->toArray();
            }else{
                $status = false;
                $message = "Cannot Create school";
                $response_array = ['data' =>[]];
            }
            return (new CraydelResponseHelper())->craydelSuccessResponse($status, $message, $response_array);
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * GET a single school /schools/id
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $school = School::find($id);
            if(!empty($school)) {
                $status = true;
                $message = "School successfully created";
                $resource = new Item($school, new SchoolTransformer());
                $responseData = $this->fractal->createData($resource)->toArray();
            }else{
                $status = false;
                $message = "School Does not exist";
                $responseData = ['data'=>array()];
            }
            return (new CraydelResponseHelper())->craydelSuccessResponse($status, $message, $responseData);
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * Get the student details
    */
    public function studentDetails(string $student_email): JsonResponse{
        try{
            $student_email = HelperFunctions::toEmailAddress($student_email);

            if(empty($student_email)){
                throw new Exception("Missing student email.");
            }

            if(!DB::table((new Student())->getTable())->where('student_email', $student_email)->exists()){
                throw new Exception("Student does not exists.");
            }

            $student = Student::with(['school'])
                ->where('student_email', $student_email)
                ->first();

            return (new CraydelResponseHelper())->craydelSuccessResponse(
                true,
                'Has details',[
                    'student' => $student
                ]
            );
        }catch (Exception $exception){
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }
}
