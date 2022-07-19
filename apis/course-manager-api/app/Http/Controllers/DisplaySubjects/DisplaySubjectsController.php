<?php /** @noinspection ALL */

namespace App\Http\Controllers\DisplaySubjects;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DisplaySubjects\Commands\DeleteDisplaySubjectController;
use App\Http\Controllers\DisplaySubjects\Commands\UpdateDisplaySubjectController;
use App\Http\Controllers\DisplaySubjects\Queries\SelectDisplaySubjectController;
use App\Http\Controllers\Subjects\Commands\DeleteSubjectCommandController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\DisplaySubjects\Commands\CreateDisplaySubjectsCommandController;
use App\Http\Controllers\DisplaySubjects\Queries\GetDisciplineSubjectsQueryController;
use Illuminate\Http\Request;

class DisplaySubjectsController
{
    use CanUploadImage, CanLog, CanCache, CanPaginate, CanRespond;

    private  GetDisciplineSubjectsQueryController $getDisciplineSubjectsQueryController;
    private  CreateDisplaySubjectsCommandController $createDisplaySubjectsCommandController;
    private  SelectDisplaySubjectController  $selectDisplaySubjectController;
    private  UpdateDisplaySubjectController  $updateDisplaySubjectController;
    private DeleteSubjectCommandController  $deleteSubjectCommandController;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createDisplaySubjectsCommandController = new CreateDisplaySubjectsCommandController($this);
        $this->getDisciplineSubjectsQueryController = new GetDisciplineSubjectsQueryController($this);
        $this->selectDisplaySubjectController = new SelectDisplaySubjectController($this);
        $this->updateDisplaySubjectController = new UpdateDisplaySubjectController($this);
        $this->deleteDisplaySubjectController = new DeleteDisplaySubjectController($this);
    }

    public function create(Request $request): JsonResponse
    {
        return $this->createDisplaySubjectsCommandController->create($request);
    }
    public function getSubjectsByDiscipline(Request $request, string $discipline_id): JsonResponse
    {
        return $this->getDisciplineSubjectsQueryController->get($request, $discipline_id);
    }
    public function select(Request $request, string $display_subject_id): JsonResponse
    {
        return $this->selectDisplaySubjectController->get($request,$display_subject_id);
    }
    public function update(Request $request, string $display_subject_id): JsonResponse
    {
        return $this->updateDisplaySubjectController->update($request,$display_subject_id);
    }
    public function delete($display_subject_id): JsonResponse
    {
        return $this->deleteDisplaySubjectController->delete($display_subject_id);
    }







}
