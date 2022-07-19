<?php
namespace App\Http\Controllers\Administration\Commands;

use App\Events\InstitutionPublishedEvent;
use App\Http\Controllers\CraydelTypes\CraydelInstitutionType;
use App\Http\Controllers\Helpers\ManageSearchEngineDataHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Institution;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;

class PushInstitutionDataToSearchEngineCommand
{
    use CanLog;

    /**
     * @var CraydelInstitutionType $institution
    */
    protected $institution;

    /**
     * @var array $search_engine_object
     */
    private $search_engine_object;

    /**
     * Constructor
     * @param string|null $institution_code
     */
    public function __construct(?string $institution_code)
    {
        $this->institution = (new CraydelInstitutionType($institution_code));
    }

    /**
     * Make search engine object
     *
     * @return $this
     */
    public function make(): PushInstitutionDataToSearchEngineCommand
    {
        try{
            if(is_null($this->institution)){
                throw new Exception('Invalid institution object');
            }

            $this->search_engine_object = [
                "institution_type" => $this->institution->getInstitutionTypeName(),
                "institution_name" => $this->institution->getInstitutionName(),
                "description" => $this->institution->getDescription(),
                "country" => $this->institution->getCountryName(),
                "city" => $this->institution->getCity(),
                "continental_ranking" => $this->institution->getContinentalRanking(),
                "country_ranking" => $this->institution->getCountryRanking(),
                "global_ranking" => $this->institution->getGlobalRanking(),
                "internal_system_ranking" => $this->institution->getSystemInternalRanking(),
                "continent" => $this->institution->getCountry()->continent,
                "logo_url" => $this->institution->getLogoUrl(),
                "logo_url_small" => $this->institution->getLogoUrlSmall(),
                "accredited_by_acronym" => $this->institution->getAccreditedByAcronym(),
                "accredited_by" => $this->institution->getAccreditedBy(),
                "accreditation_body_url" => $this->institution->getAccreditationBodyUrl(),
                "number_of_courses" => $this->institution->getNumberOfCourses(),
                "ownership" => $this->institution->getOwnershipType(),
                "institution_name_slug" => $this->institution->getInstitutionNameSlug(),
                "institution_code" => $this->institution->getInstitutionCode(),
                "is_featured" => $this->institution->getIsFeatured(),
                "primary_image" => $this->institution->getPrimaryImage(),
                "available_course_categories" => $this->institution->getAcademicDisciplines()
            ];

            return $this;
        }catch (Exception $exception){
            $this->logException($exception);

            return $this;
        }catch (GuzzleException $e){
            $this->logException($e);

            return $this;
        }
    }

    /**
     * Push data to the search engine
     *
     * @return void
    */
    public function push()
    {
        try{
            $result = (new ManageSearchEngineDataHelper(
                $this->institution->getInstitutionCode(),
                $this->search_engine_object
            ))->push();

            if($result->status){
                event(new InstitutionPublishedEvent($this->institution->getInstitutionCode()));

                $this->update();
            }else{
                $this->update($result->message);
            }
        }catch(Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Make the course as indexed
     *
     * @param string|null $error
     * @return void
     */
    private function update(?string $error = null){
        try {
            DB::transaction(function () use($error){
                DB::table((new Institution())->getTable())
                    ->where('institution_code', $this->institution->getInstitutionCode())
                    ->update([
                        'indexing_object_id' => $this->institution->getInstitutionCode(),
                        'has_updates' => !is_null($error) && !empty($error) ? 1 : 0,
                        'is_picked_for_indexing' => 0,
                        'time_taken_to_index' => DB::raw("timediff('".Carbon::now()->toDateTimeString()."', time_picked_for_indexing)"),
                        'indexing_error' => !is_null($error) && !empty($error)  ? trim($error) : null
                    ]);
            });
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    public function delete()
    {
        try{
            $result = (new ManageSearchEngineDataHelper(
                $this->institution->getInstitutionCode(),
                $this->search_engine_object
            ))->delete();

        }catch(Exception $exception){
            $this->logException($exception);
        }
    }
}
