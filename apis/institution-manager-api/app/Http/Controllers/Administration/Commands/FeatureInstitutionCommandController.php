<?php
namespace App\Http\Controllers\Administration\Commands;

use App\Events\InstitutionUpdatedEvent;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Institution;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeatureInstitutionCommandController
{
    /**
     * @var InstitutionController
     */
    protected $institution_controller;

    /**
     * Constructor
     * @param InstitutionController $institution_controller
     */
    public function __construct(InstitutionController $institution_controller){
        $this->institution_controller = $institution_controller;
    }

    /**
     * Feature course
     *
     * @param Request $request
     * @param string|null $institution_code
     *
     * @return JsonResponse
     */
    public function feature(Request $request, ?string $institution_code): JsonResponse
    {
        try{
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);

            if(empty($institution_code)){
                throw new Exception("Missing institution code");
            }

            if(!DB::table((new Institution())->getTable())->where('institution_code', $institution_code)->exists()){
                throw new Exception("Invalid institution code");
            }

            $current_status = null;

            DB::transaction(function () use($institution_code, &$current_status){
                $current_status = DB::table((new Institution())->getTable())
                    ->where('institution_code', trim($institution_code))
                    ->value('is_featured');

                if($current_status == 1){
                    DB::table((new Institution())->getTable())
                        ->where('institution_code', $institution_code)
                        ->update([
                            'is_featured' => 0
                        ]);
                }else{
                    DB::table((new Institution())->getTable())
                        ->where('institution_code', $institution_code)
                        ->update([
                            'is_featured' => 1
                        ]);
                }
            });

            event(new InstitutionUpdatedEvent($institution_code));

            if($current_status == 0){
                return $this->institution_controller->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.featured')
                ));
            }else{
                return $this->institution_controller->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.un_featured')
                ));
            }
        }catch (Exception $exception){
            $this->institution_controller->logException($exception);
            return $this->institution_controller->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }
}
