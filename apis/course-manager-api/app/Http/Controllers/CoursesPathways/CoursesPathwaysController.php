<?php
namespace App\Http\Controllers\CoursesPathways;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoursesPathways\Commands\BuildNewCoursesPathwaysCommandController;
use App\Http\Controllers\CoursesPathways\Commands\CreateCoursesPathwayCommandController;
use App\Http\Controllers\CoursesPathways\Commands\DeleteCoursePathwaysCommandController;
use App\Http\Controllers\CoursesPathways\Commands\UpdateCoursesPathwaysCommandController;
use App\Http\Controllers\CoursesPathways\Queries\GetCoursesDisciplinesQueryController;
use App\Http\Controllers\CoursesPathways\Queries\GetCoursesPathwayQueryController;
use App\Http\Controllers\CoursesPathways\Queries\GetDisciplineSubjectsQueryController;
use App\Http\Controllers\CoursesPathways\Queries\GetSingleCoursePathwayDetails;
use App\Http\Controllers\CoursesPathways\Queries\ListCoursesPathwaysQueryController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\CoursesPathway;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use Illuminate\Http\Request;

class CoursesPathwaysController
{
    use   CanLog, CanCache, CanPaginate, CanRespond;

    private BuildNewCoursesPathwaysCommandController $buildNewCoursesPathwaysCommandController;
    private ListCoursesPathwaysQueryController $listCoursesPathwaysQueryController;
    private CreateCoursesPathwayCommandController $createCoursesPathwayCommandController;
    private GetCoursesDisciplinesQueryController $getCoursesDisciplinesQueryController;
    private GetCoursesPathwayQueryController $getCoursesPathwayQueryController;
    private UpdateCoursesPathwaysCommandController $updateCoursesPathwaysCommandController;
    private DeleteCoursePathwaysCommandController $deleteCoursePathwaysCommandController;
    private GetDisciplineSubjectsQueryController  $getDisciplineSubjectsQueryController;
    private GetSingleCoursePathwayDetails $getSingleCoursePathwayDetails;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->buildNewCoursesPathwaysCommandController = new BuildNewCoursesPathwaysCommandController($this);
        $this->listCoursesPathwaysQueryController = new ListCoursesPathwaysQueryController($this);
        $this->createCoursesPathwayCommandController = new CreateCoursesPathwayCommandController($this);
        $this->getCoursesDisciplinesQueryController = new GetCoursesDisciplinesQueryController($this);
        $this->getCoursesPathwayQueryController = new GetCoursesPathwayQueryController($this);
        $this->updateCoursesPathwaysCommandController = new UpdateCoursesPathwaysCommandController($this);
        $this->deleteCoursePathwaysCommandController = new DeleteCoursePathwaysCommandController($this);
        $this->getDisciplineSubjectsQueryController = new GetDisciplineSubjectsQueryController($this);
        $this->getSingleCoursePathwayDetails = new GetSingleCoursePathwayDetails($this);
    }

    public function build(): JsonResponse
    {
        return $this->buildNewCoursesPathwaysCommandController->build();
    }

    public function pathways(Request $request): JsonResponse
    {
        return $this->listCoursesPathwaysQueryController->get($request);
    }

    public function create(Request $request): JsonResponse
    {
        return $this->createCoursesPathwayCommandController->create($request);
    }

    public function getCourseDisciplines(Request $request): JsonResponse
    {
        return $this->getCoursesDisciplinesQueryController->get($request);
    }

    public function getSingleCoursePathwayDetails(Request $request): JsonResponse
    {
        return $this->getSingleCoursePathwayDetails->get($request);
    }
    public function edit(string $course_pathway_id): JsonResponse
    {
        return $this->getCoursesPathwayQueryController->get($course_pathway_id);
    }

    public function update(Request $request, string $course_pathway_id): JsonResponse
    {
        return $this->updateCoursesPathwaysCommandController->update($request, $course_pathway_id);
    }

    public function delete(string $course_pathway_id): JsonResponse
    {
        return $this->deleteCoursePathwaysCommandController->delete($course_pathway_id);
    }

    public function getSubjectDiscipline(Request $request, string $discipline_id): JsonResponse
    {
        return $this->getDisciplineSubjectsQueryController->get($request, $discipline_id);
    }

    Public function  getCareerPathways (Request $request) : JsonResponse{

        return $this->listCoursesPathwaysQueryController->getCareerPathways($request);

    }

}
