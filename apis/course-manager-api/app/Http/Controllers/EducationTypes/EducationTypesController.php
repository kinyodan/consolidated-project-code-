<?php /** @noinspection ALL */

namespace App\Http\Controllers\EducationTypes;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EducationTypes\Commands\CreateEducationTypeCommandController;
use App\Http\Controllers\EducationTypes\Commands\DeleteEducationTypeCommandController;
use App\Http\Controllers\EducationTypes\Commands\UpdateEducationTypeCommandController;
use App\Http\Controllers\EducationTypes\Queries\GetEducationTypeQueryController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\EducationTypes\Queries\ListEducationTypeQueryController;
use Illuminate\Http\Request;

class EducationTypesController
{
    use CanUploadImage, CanLog, CanCache, CanPaginate, CanRespond;

    private ListEducationTypeQueryController $listEducationTypeQueryController;
    private CreateEducationTypeCommandController $createEducationTypeCommandController;
    private GetEducationTypeQueryController $getEducationTypeQueryController;
    private UpdateEducationTypeCommandController $updateEducationTypeCommandController;
    private DeleteEducationTypeCommandController  $deleteEducationTypeCommandController;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listEducationTypeQueryController = new ListEducationTypeQueryController($this);
        $this->createEducationTypeCommandController = new CreateEducationTypeCommandController($this);
        $this->getEducationTypeQueryController = new GetEducationTypeQueryController($this);
        $this->updateEducationTypeCommandController = new UpdateEducationTypeCommandController($this);
        $this->deleteEducationTypeCommandController = new DeleteEducationTypeCommandController($this);
    }

    public function educations(Request $request): JsonResponse
    {
        return $this->listEducationTypeQueryController->get($request);
    }

    public function create(Request $request): JsonResponse
    {
        return $this->createEducationTypeCommandController->create($request);
    }

    public function edit(string $education_type_id): JsonResponse
    {
        return $this->getEducationTypeQueryController->get($education_type_id);
    }

    public  function  update(Request $request,string  $education_type_id): JsonResponse{
        return $this->updateEducationTypeCommandController->update($request,$education_type_id);
    }

    public function delete(string $education_type_id): JsonResponse
    {
        return $this->deleteEducationTypeCommandController->delete($education_type_id);
    }

    /**
     * @param string $country_code
    */
    public function getEducationTypesInCountryByCountryCode(string $country_code){
        return $this->getEducationTypeQueryController->getEducationTypesInCountryByCountryCode($country_code);
    }
}
