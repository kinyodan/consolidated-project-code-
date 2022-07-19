<?php

namespace App\Http\Controllers\ManageSchools\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\DateHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\School;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;


class ListSchoolsQueryController
{
    use CanCache, CanRespond, CanLog, CanPaginate;

    /**
     * Handle the classes list quest
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        try {
            $schools = School::where('status', '=', 1);
            $search = $request->input('search');
            if (!CraydelHelperFunctions::isNull($search)) {
                $schools = $schools->where('school_name', 'like', '%' . $search . '%');
            }
            $currentPage = $this->getCurrentPage($request);
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $schools->count('id');
            $this->itemsPerPage = $request->input('items_per_page', config('craydle.items_per_page', 10));

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $sort_by = $request->input('sort_by');
            $sort_direction = $request->input('sort_direction');

            if (!CraydelHelperFunctions::isNull($sort_by) && !CraydelHelperFunctions::isNull($sort_direction)) {
                $schools = $schools->orderBy($sort_by, $sort_direction);
            } else {
                $schools = $schools->orderBy('id', 'desc');
            }

            $schools = $schools
                ->simplePaginate($this->itemsPerPage);

            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('schools.success.listed'),
                (object)[
                    'items' => collect($schools->items())->map(function ($school) {
                        return [
                            'id' => $school->id ?? null,
                            'school_name' => $school->school_name,
                            'school_email' => $school->school_email ?? null,
                            'country' =>  $school->country->name ?? null,
                            'school_phone' => $school->school_phone,
                            'school_address' => $school->school_address,
                            'school_physical_address' => $school->school_physical_address,
                            'school_code' => $school->school_code,
                            'country_code' => $school->country_code,
                            'school_website_url' => $school->school_website_url,
                            'school_logo_url' => $school->school_logo_url,
                            'discount_value' => $school->discount_value,
                            'parent_school_id' => $school->parent_school_id,
                            'curriculum_id' => $school->curriculum_id ?? null,
                            'allowed_license_count'=> $school->allowed_license_count ?? null,
                            'created_at' => isset($school->created_at) && CraydelHelperFunctions::isDate($school->created_at) ?
                                DateHelper::makeDisplayDateTime($school->created_at, 'd-m-Y') : null,
                            'updated_at' => isset($school->updated_at) && CraydelHelperFunctions::isDate($school->updated_at) ?
                                DateHelper::makeDisplayDateTime($school->updated_at, 'd-m-Y') : null,
                        ];
                    }),
                    'items_per_page' => $this->itemsPerPage,
                    'current_page' => $this->currentPaginationPage,
                    'previous_page' => $this->previousPage(),
                    'next_page' => $this->nextPage(),
                    'number_of_pages' => $this->getTotalNumberOfPages(),
                    'items_count' => $this->totalNumberOfEntities
                ]
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
