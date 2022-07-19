<?php
namespace App\Http\Controllers\Helpers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\SchoolStream;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvitesImportHelper implements ToCollection, WithHeadingRow
{
    /**
     * @var int $school_id
    */
    private int $school_id;

    /**
     * @param int $school_id
    */
    public function  __construct(int $school_id)
    {
        $this->school_id = $school_id;
    }

    /**
     * @param Collection $collection
     * @return void
     */
    public function collection(Collection $collection)
    {
        /*$data = [];

        foreach ($collection as $row){
            $student_name = $row['student_name'] ?? null;
            $student_email = $row['student_email'] ?? null;
            $student_class = $row['student_class'] ?? null;
            $student_class_stream = $row['student_class_stream'] ?? null;
            $class_id = $this->getClassID($student_class);
            $stream_id = $this->getStreamID($student_class_stream);

            if(!empty($student_name) && !empty($student_email) && !empty($class_id) && !empty($stream_id)){
                $data[] = [
                    'school_id' => $this->school_id,
                    'student_name' => $student_name,
                    'student_email' => $student_email,
                    'class_id' => $class_id,
                    'stream_id' => $stream_id,
                    'is_invite_sent' => 0,
                    'created_at' => Carbon::now()->toDateTimeString()
                ];
            }
        }

        $data = array_chunk($data, 100);

        foreach ($data as $chunk){
            DB::table((new Student())->getTable())
                ->insertOrIgnore($chunk);
        }*/
    }

    /**
     * Get the class ID
    */
    protected function getClassID(string $class_name){
        $class_id = DB::table((new SchoolClass())->getTable())
            ->where('class_name', CraydelHelperFunctions::toCleanString($class_name))
            ->where('school_id', $this->school_id)
            ->value('id');

        if(empty($class_id)){
            return DB::table((new SchoolClass())->getTable())->insertGetId([
                'class_name' => CraydelHelperFunctions::toCleanString($class_name),
                'status' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'school_id' => $this->school_id
            ]);
        }else{
            return $class_id;
        }
    }

    /**
     * Get the stream ID
    */
    protected function getStreamID(string $stream_name){
        $stream_id = DB::table((new SchoolStream())->getTable())
            ->where('stream_name', CraydelHelperFunctions::toCleanString($stream_name))
            ->where('school_id', $this->school_id)
            ->value('id');

        if(empty($stream_id)){
            return DB::table((new SchoolStream())->getTable())->insertGetId([
                'stream_name' => CraydelHelperFunctions::toCleanString($stream_name),
                'status' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'school_id' => $this->school_id
            ]);
        }else{
            return $stream_id;
        }
    }
}
