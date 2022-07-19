<?php
namespace App\Http\Controllers\Administration\Commands\Alumni;

use App\Events\InstitutionUpdatedEvent;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\DeleteImagesFromTheCDN;
use App\Models\Institution;
use App\Models\InstitutionAlumnus;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteInstitutionAlumnusController
{
    use CanLog;

    /**
     * @var InstitutionController
     */
    protected $institutionController;

    /**
     * Constructor
     * @param InstitutionController $institutionController
     */
    public function __construct(InstitutionController $institutionController){
        $this->institutionController = $institutionController;
    }

    /**
     * Delete the institution alumnus
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $alumnus_id
     * @return JsonResponse
     */
    public function delete(Request $request, ?string $institution_code, ?int $alumnus_id): JsonResponse
    {
        try{
            if(empty($institution_code)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.missing_institution_code')
                );
            }

            if(!DB::table((new Institution())->getTable())->where('institution_code', $institution_code)->exists()){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.invalid_institution_code')
                );
            }

            if(empty($alumnus_id)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.missing_alumnus_id')
                );
            }

            if(!DB::table((new InstitutionAlumnus())->getTable())->where('id', $alumnus_id)->where('institution_code', $institution_code)->exists()){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_alumnus_id')
                );
            }

            $is_deleted = false;

            DB::transaction(function () use($institution_code, $alumnus_id, &$is_deleted){
                $is_deleted = DB::table((new InstitutionAlumnus())->getTable())
                    ->where('id', $alumnus_id)
                    ->where('institution_code', $institution_code)
                    ->delete();
            });

            if($is_deleted){
                event(new InstitutionUpdatedEvent($institution_code));

                dispatch((new DeleteImagesFromTheCDN(
                    InstitutionController::getInstitutionAlumnusImages($alumnus_id)
                )))->onQueue('delete_images_to_cdn');

                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.alumnus_deleted')
                ));
            }else{
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.error_deleting_alumnus')
                );
            }
        }catch (Exception $exception){
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
