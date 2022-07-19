<?php

namespace App\Http\Controllers\ManageSchools\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\School;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateSchoolDetailsCommandController
{
    use CanLog, CanRespond;

    /**
     * Add school class
     *
     * @param Request $request
     * @param $school_id
     * @return JsonResponse
     */
    public function handle(Request $request,$school_id): JsonResponse
    {
        try {

            $validation = (new ValidateSchoolDetailsCommandController())->handle($request, true);
            if (!$validation->status) {
                throw new Exception($validation->message ?? "Error while validating the school details");
            }

            $saved = false;

            DB::transaction(function () use ($validation, &$saved) {
                $saved = DB::table((new School())->getTable())
                    ->update($validation->data);
            });

            if (!$saved) {
                throw new Exception("Error while saving the school name details.");
            }

            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('schools.success.updated')
            ));
        } catch (Exception $exception) {
            self::logException($exception);

            return $this->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
