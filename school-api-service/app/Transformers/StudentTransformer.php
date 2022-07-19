<?php

namespace App\Transformers;

use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\Student;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class StudentTransformer extends TransformerAbstract
{
    public function transform(Student $student)
    {
        return [
            'id' => (int)$student->id,
            'school_id'=> $student->school_id,
            'school_name'=> $student->school->school_name,
            'class_id'=> $student->class_id,
            'class_name'=> isset($student->class_id) && $student->class_id !=0 ? $student->studentClass->class_name : "",
            'stream_id'=> $student->stream_id,
            'stream_name'=> isset($student->stream_id) && $student->stream_id != 0 ? $student->studentStream->stream_name : "",
            'student_name' => $student->student_name,
            'student_email' => $student->student_email,
            'student_phone' => $student->student_phone,
            'craydel_user_id' => $student->craydel_user_id,
            'student_address' => $student->student_address,
            'is_craydel_account_created' => $student->is_craydel_account_created,
            'is_invite_sent' => $student->is_invite_sent,
            'has_done_career_counselling' => $student->has_done_career_counselling,
            'has_applied_for_course' => $student->has_applied_for_course,
            'is_account_activated' => $student->is_account_activated,
            'has_subscribed_for_assessment' => $student->has_subscribed_for_assessment,
            'student_assessment_code' => $student->student_assessment_code ?? "" ,
            'student_opportunity_code' => $student->student_opportunity_code ?? "" ,
            'student_opportunity_status' => $student->student_opportunity_status ?? "",
            'student_opportunity_institution' => $student->student_opportunity_institution ?? "",
            'student_opportunity_institution_location' => $student->student_opportunity_institution_location ?? "",
            'student_opportunity_intake' => $student->student_opportunity_intake ?? "",
            'student_opportunity_course' => $student->student_opportunity_course ?? "",
            'student_lead_code' => $student->student_lead_code ?? "",
            'student_lead_status' => $student->student_lead_status ?? "",
            'created_at' => !is_null($student->created_at) ? $student->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => !is_null($student->updated_at) ? $student->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
