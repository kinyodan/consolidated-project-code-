<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\CraydelResponseHelper;
use App\Http\Controllers\Helpers\HelperFunctions;
use App\Http\Controllers\Helpers\InvitesImportHelper;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentStream;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class BulkImportStudentInvitesController
{
    /**
     * @var array $allowedImageMimeTypes
     */
    public array $allowedImageMimeTypes = [
        'text/csv', 'application/csv',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel'
    ];

    /**
     * @var array $template_fields
    */
    private array $template_fields = [
        'student_name',
        'student_email',
        'student_class',
        'student_class_stream'
    ];

    /**
     * Import file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function import(Request $request): JsonResponse
    {
        try{
            $school_admin = SchoolAdmin::where('craydel_user_id', $request->get('user_code'))->first();
            $school_id = $school_admin->school_id;

            if(is_null($school_id)){
                throw new Exception("Missing school ID.");
            }

            if(!$request->hasFile('invite_list')){
                throw new Exception("Missing invite list file.");
            }

            $file = $request->file('invite_list');
            $file_mime_type = $file->getClientMimeType();

            if (!in_array($file_mime_type, $this->allowedImageMimeTypes)) {
                throw new Exception("The file type you uploaded ({$file_mime_type}) is not allowed");
            }

            $response = Excel::toArray(new InvitesImportHelper($school_id), $request->file('invite_list'));

            return $this->store($response, $school_id);
        }catch (Exception $exception){
            return (new CraydelResponseHelper())->craydelErrorResponse($exception);
        }
    }

    /**
     * Store data
     *
     * @param array $imported_records
     * @param int $school_id
     * @return JsonResponse
     * @throws Exception
     */
    private function store(array $imported_records, int $school_id): JsonResponse
    {
        $data = [];
        $batch_number = Str::random(10);
        $number_of_rows = 0;

        $first_row = $imported_records[0][0] ?? null;

        if(is_null($first_row)){
            throw new Exception("The file you uploaded is empty");
        }

        $first_row_keys = array_keys($first_row);
        $first_row_keys_diff = array_diff($first_row_keys, $this->template_fields);

        if(count($first_row_keys_diff) > 0){
            throw new Exception("Invalid student invite template was used. Try again");
        }

        foreach ($imported_records as $chunk){
            foreach ($chunk as $row){
                $number_of_rows++;
                $student_name = $row['student_name'] ?? null;
                $student_email = $row['student_email'] ?? null;
                $student_class = $row['student_class'] ?? null;
                $student_class_stream = $row['student_class_stream'] ?? null;

                if(!empty($student_name) && !empty($student_email)){
                    $class_id = $this->getClassID($student_class, $school_id);
                    $stream_id = $this->getStreamID($student_class_stream, $school_id);

                    $data[] = [
                        'school_id' => $school_id,
                        'student_name' => $student_name,
                        'student_email' => $student_email,
                        'class_id' => $class_id,
                        'stream_id' => $stream_id,
                        'is_invite_sent' => 0,
                        'import_batch_no' => $batch_number,
                        'created_at' => Carbon::now()->toDateTimeString()
                    ];
                }
            }
        }

        $data = array_chunk($data, 100);

        foreach ($data as $chunk){
            DB::table((new Student())->getTable())
                ->insertOrIgnore($chunk);
        }

        $number_of_imported_rows = DB::table((new Student())->getTable())
            ->where('import_batch_no', $batch_number)
            ->count('id');

        $diff = $number_of_rows - $number_of_imported_rows;

        return (new CraydelResponseHelper())->craydelSuccessResponse(
            true,
            $number_of_rows == $number_of_imported_rows ? "Successfully imported <strong>{$number_of_imported_rows}</strong> records out of <strong>{$number_of_rows}</strong" : "Failed to import <strong>{$diff}</strong> records out of <strong>{$number_of_rows}</strong>",[
                'uploaded_records' => $number_of_rows,
                'imported_records' => $number_of_imported_rows
            ]
        );
    }

    /**
     * Get the class ID
     */
    protected function getClassID(string $class_name, int $school_id){
        $class_id = DB::table((new StudentClass())->getTable())
            ->where('class_name', HelperFunctions::toCleanString($class_name))
            ->where('school_id', $school_id)
            ->value('id');

        if(empty($class_id)){
            return DB::table((new StudentClass())->getTable())->insertGetId([
                'class_name' => HelperFunctions::toCleanString($class_name),
                'status' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'school_id' => $school_id
            ]);
        }else{
            return $class_id;
        }
    }

    /**
     * Get the stream ID
     */
    protected function getStreamID(string $stream_name, int $school_id){
        $stream_id = DB::table((new StudentStream())->getTable())
            ->where('stream_name', HelperFunctions::toCleanString($stream_name))
            ->where('school_id', $school_id)
            ->value('id');

        if(empty($stream_id)){
            return DB::table((new StudentStream())->getTable())->insertGetId([
                'stream_name' => HelperFunctions::toCleanString($stream_name),
                'status' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'school_id' => $school_id
            ]);
        }else{
            return $stream_id;
        }
    }

    /**
     * Check if the school has licenses
     * @throws Exception
     */
    protected function _checkIfSchoolHaAvailableLicenses(int $school_id, int $imported)
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
