<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\CraydelResponseHelper;
use App\Http\Controllers\Helpers\HelperFunctions;
use App\Models\SchoolAdmin;
use App\Transformers\SchoolAdminTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use PHPUnit\Util\Exception;
use Throwable;

class SchoolAdminsController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    private $fractal;

    public function __construct()
    {
        $this->fractal = new Manager();
    }

    /**
     * GET /schools/admins
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $paginator = SchoolAdmin::paginate();
            $schoolAdmins = $paginator->getCollection();
            $resource = new Collection($schoolAdmins, new SchoolAdminTransformer());
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
            $responseData = $this->fractal->createData($resource)->toArray();

            return (new CraydelResponseHelper())->craydelSuccessResponse(true, "", $responseData);
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * Store the school admin details
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            if(!HelperFunctions::isEmail($request->input('admin_email'))){
                throw new Exception("Invalid administrator email");
            }

            if(DB::table((new SchoolAdmin())->getTable())->where('admin_email', $request->input('admin_email'))->exists()){
                throw new Exception("Duplicate administrator email address");
            }

            if(DB::table((new SchoolAdmin())->getTable())->where('admin_phone', $request->input('admin_phone'))->exists()){
                throw new Exception("Duplicate administrator phone number");
            }

            $validator = Validator::make($request->all(), [
                'school_id' => 'required',
                'admin_name' => 'required',
                'admin_email' => 'required|email|unique:school_admins,admin_email',
                'admin_phone' => 'required',
                'admin_address' => 'required',
            ]);

            if ($validator->fails()) {
                $response_array = ['data' => $validator->messages()];
                return (new CraydelResponseHelper())->craydelSuccessResponse(false, "", $response_array);
            }
        } catch (Throwable $exception) {
            DB::table((new SchoolAdmin())->getTable())->where('admin_email', HelperFunctions::toEmailAddress($request->input('admin_email')))->delete();
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }

        try{
            $insertData = $validator->validated();
            $schoolAdmin = SchoolAdmin::create($insertData);

            if(!empty($schoolAdmin)){
                //create a real user on SSO
                $this->createAdministratorUserAccount($schoolAdmin);

                $status = true;
                $message = "School Admin successfully created";
                $resource = new Item($schoolAdmin, new SchoolAdminTransformer());
                $response_array = $this->fractal->createData($resource)->toArray();
            }else{
                $status = false;
                $message = "Cannot Create school admin";
                $response_array = ['data' =>[]];
            }

            return (new CraydelResponseHelper())->craydelSuccessResponse($status, $message, $response_array);
        } catch (Throwable $exception) {
            DB::table((new SchoolAdmin())->getTable())->where('admin_email', HelperFunctions::toEmailAddress($request->input('admin_email')))->delete();
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * GET a single school /school/admins/id
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $schoolAdmin = SchoolAdmin::find($id);
            if(!empty($schoolAdmin)) {
                $status = true;
                $message = "School Admin successfully retrieved";
                $resource = new Item($schoolAdmin, new SchoolAdminTransformer());
                $responseData = $this->fractal->createData($resource)->toArray();
            }else{
                $status = false;
                $message = "School admin Does not exist";
                $responseData = ['data'=>array()];
            }
            return (new CraydelResponseHelper())->craydelSuccessResponse($status, $message, $responseData);
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * GET a single school details from user token
     *
     * @return JsonResponse
     */
    public function getSchoolDetails(Request $request): JsonResponse
    {
        try {
            $schoolAdmin = SchoolAdmin::where('craydel_user_id', $request->get('user_code'))->first();
            if(!empty($schoolAdmin) && !empty($schoolAdmin->school)) {
                $status = true;
                $message = "School details successfully retrieved";
                $responseData = ['data'=>$schoolAdmin->school];
            }else{
                $status = false;
                $message = "School does not exist";
                $responseData = ['data'=>array()];
            }
            return (new CraydelResponseHelper())->craydelSuccessResponse($status, $message, $responseData);
        } catch (Throwable $exception) {
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * Create administrator account
     * @throws \Exception
     */
    private function createAdministratorUserAccount($user_details)
    {
        $invite = Http::post(
            config('services.accounts_service.create_invite'),[
                'service_code' => 'SRVLDSUWYD65N',
                'access_group_code' => 'CAGY7NRVAD5N8',
                'email_address' => $user_details->admin_email ?? null,
                'invited_by' => 'Craydel Administrator'
            ]
        );

        $invite = json_decode($invite->body());
        //check for invite error
        if($invite->status){

            //create the user account
            $password = HelperFunctions::makeStrongPassword(10);
            $data = [
                'user_provider' => 'custom',
                'service' => 'SRVLDSUWYD65N',
                'group' => 'CAGY7NRVAD5N8',
                'role' => 'CARXXXV8XXGGP',
                'invite' => $invite->data->invite_code ?? null,
                'email' => $user_details->admin_email ?? null,
                'password' => $password,
                'name' => $user_details->admin_name ?? null,
                'country' => 'KE',
                'locale' => 'en',
                'mobile_number' => $user_details->admin_phone,
                'redirect' => config('services.schools_website'),
                'auto_activate' => 1
            ];

            $response = Http::withHeaders([
                'country' => 'KE',
                'auto_activate' => 1
            ])->post(
                config('services.accounts_service.create_user'),
                $data
            );

            $response = json_decode($response->body());
            if($response->status){
                try {
                    SchoolAdmin::where('id', $user_details->id)->update(
                        [
                            'craydel_user_id' => $response->data->user_code,
                            'is_craydel_account_created' => 1,
                            'is_account_activated' => 1,
                            'is_invite_sent' => 1,
                        ]
                    );
                }catch (Throwable $e){
                    throw new Exception($e->getMessage());
                }
            }else{
                throw new Exception(isset($response->message) && !empty($response->message) ? $response->message : "Error creating the admin account");
            }
        }else{
            throw new Exception(isset($invite->message) && !empty($invite->message) ? $invite->message : "Error creating the admin account");
        }
    }
}
