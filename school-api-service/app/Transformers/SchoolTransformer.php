<?php

namespace App\Transformers;

use App\Models\School;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class SchoolTransformer extends TransformerAbstract
{
    public function transform(School $school): array
    {
        return [
            'id'=> isset($school->id) ? (int)$school->id : null,
            'school_name' => $school->school_name ?? null,
            'school_email' => $school->school_email ?? null,
            'school_phone' => $school->school_phone ?? null,
            'school_address' => $school->school_address ?? null,
            'school_physical_address'=> $school->school_physical_address ?? null,
            'is_verified'=> $school->is_verified ?? null,
            'status'=> $school->status ?? null,
            'created_at'=> isset($school->created_at) ? $school->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'=> isset($school->updated_at) ? $school->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
