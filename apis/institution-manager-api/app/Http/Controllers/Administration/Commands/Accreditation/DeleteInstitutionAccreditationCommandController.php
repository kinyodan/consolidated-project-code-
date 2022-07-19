<?php
namespace App\Http\Controllers\Administration\Commands\Accreditation;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\DeleteImagesFromTheCDN;
use App\Models\InstitutionAccreditation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteInstitutionAccreditationCommandController
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
     * Update accreditation to the institution
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $accreditation_id
     * @return JsonResponse
     */
    public function delete(Request $request, ?string $institution_code, ?int $accreditation_id): JsonResponse
    {
        try {
            $accreditation = DB::table((new InstitutionAccreditation())->getTable())
                ->where('id', $accreditation_id)
                ->where('institution_code', $institution_code)
                ->first();

            if(!isset($accreditation->id)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.invalid_accreditation_id')
                );
            }

            $accreditation_images = InstitutionController::getInstitutionAccreditationImages($accreditation_id);

            $deleted = false;

            DB::transaction(function () use($institution_code, $accreditation_id, &$deleted){
                $deleted = DB::table((new InstitutionAccreditation())->getTable())
                    ->where('id', $accreditation_id)
                    ->where('institution_code', $institution_code)
                    ->delete();
            });

            if($deleted){
                dispatch((new DeleteImagesFromTheCDN($accreditation_images)))->onQueue('delete_images_to_cdn');

                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.accreditation_deleted')
                ));
            }

            throw new Exception(
                LanguageTranslationHelper::translate('institutions.errors.accreditation.error_deleting_accreditation')
            );
        }catch (Exception $exception){
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }
}
