<?php
namespace App\Http\Controllers\Administration;

use App\Events\InstitutionGalleryImageUploadedEvent;
use App\Events\InstitutionLogoImageUploadedEvent;
use App\Http\Controllers\Administration\Commands\Accreditation\AddInstitutionAccreditationCommandController;
use App\Http\Controllers\Administration\Commands\Accreditation\DeleteInstitutionAccreditationCommandController;
use App\Http\Controllers\Administration\Commands\Accreditation\GetInstitutionAccreditationsCommandController;
use App\Http\Controllers\Administration\Commands\Accreditation\UpdateInstitutionAccreditationCommandController;
use App\Http\Controllers\Administration\Commands\AddItemToInstitutionGalleryCommandController;
use App\Http\Controllers\Administration\Commands\Alumni\AddInstitutionAlumnusCommandController;
use App\Http\Controllers\Administration\Commands\Alumni\DeleteInstitutionAlumnusController;
use App\Http\Controllers\Administration\Commands\Alumni\UpdateInstitutionAlumnusCommandController;
use App\Http\Controllers\Administration\Commands\DeleteInstitutionCommandController;
use App\Http\Controllers\Administration\Commands\DeleteInstitutionGalleryAssetCommandController;
use App\Http\Controllers\Administration\Commands\FeatureInstitutionCommandController;
use App\Http\Controllers\Administration\Commands\FeatureInstitutionGalleryAssetCommandController;
use App\Http\Controllers\Administration\Commands\Highlight\AddInstitutionHighlightCommandController;
use App\Http\Controllers\Administration\Commands\Highlight\DeleteInstitutionHighlightCommandController;
use App\Http\Controllers\Administration\Commands\Highlight\UpdateInstitutionHighlightCommandController;
use App\Http\Controllers\Administration\Commands\PublishInstitutionCommandController;
use App\Http\Controllers\Administration\Commands\SubmitReviewCommandController;
use App\Http\Controllers\Administration\Commands\UnPublishInstitutionCommandController;
use App\Http\Controllers\Administration\Queries\Accreditation\GetInstitutionAccreditationDetailsQueryController;
use App\Http\Controllers\Administration\Queries\Alumni\BuildNewAlumnusRequestCommandController;
use App\Http\Controllers\Administration\Queries\Alumni\GetAlumniListController;
use App\Http\Controllers\Administration\Commands\Alumni\BulkUploadAlumnusCommandController;
use App\Http\Controllers\Administration\Commands\Alumni\ReceiveAlumniQuestionResponseCommandController;
use App\Http\Controllers\Administration\Commands\Alumni\ReceiveAlumniReviewCommandController;
use App\Http\Controllers\Administration\Commands\Alumni\UpdateAlumnusProfileCommandController;
use App\Http\Controllers\Administration\Queries\Alumni\GetAlumniQuestionsQueryController;
use App\Http\Controllers\Administration\Queries\Alumni\GetAlumnusDetailsQueryController;
use App\Http\Controllers\Administration\Queries\Alumni\GetInstitutionAlumniQueryController;
use App\Http\Controllers\Administration\Queries\Alumni\GetInstitutionAlumnusDetailsQueryController;
use App\Http\Controllers\Administration\Queries\Alumni\GetAlumnusResponsesDetailsQueryController;
use App\Http\Controllers\Administration\Queries\Alumni\GetAlumnusReviewDetailsQueryController;
use App\Http\Controllers\Administration\Queries\GetInstitutionGalleyQueryController;
use App\Http\Controllers\Administration\Queries\Highlights\GetInstitutionHighlightDetailsQueryController;
use App\Http\Controllers\Administration\Queries\Highlights\GetInstitutionHighlightsQueryController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\InstitutionTypeHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Administration\Commands\BulkUploadInstitutionsCommandController;
use App\Http\Controllers\Administration\Commands\CreateInstitutionCommandController;
use App\Http\Controllers\Administration\Commands\UpdateInstitutionCommandController;
use App\Http\Controllers\Administration\Commands\CreateQuestionCategoryCommandController;
use App\Http\Controllers\Administration\Commands\CreateQuestionCommandController;
use App\Http\Controllers\Administration\Queries\ListInstitutionsQueryController;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadExcel;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Jobs\UploadInstitutionLogoToCDNJob;
use App\Models\Institution;
use App\Models\InstitutionAccreditation;
use App\Models\InstitutionAlumnus;
use App\Models\InstitutionGallery;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstitutionController
{
    use CanLog, CanRespond, CanUploadImage, CanUploadExcel;

    /**
     * Gallery asset types
     */
    const GALLERY_IMAGE_TYPE = 'Image';
    const Gallery_VIDEO_LINK_TYPE = 'VideoLink';
    public static $institution_asset_types = ['Image', 'VideoLink'];

    /**
     * @var ListInstitutionsQueryController
     */
    private $listInstitutionsController;

    /**
     * @var CreateInstitutionCommandController
     */
    private $createInstitutionCommandController;

    /**
     * @var UpdateInstitutionCommandController
     */
    private $updateInstitutionCommandController;

    /**
     * @var BulkUploadInstitutionsCommandController
     */
    private $bulkUploadInstitutionsCommandController;

    /**
     * @var SubmitReviewCommandController
     */
    private $submitReviewCommandController;

    /**
     * @var FeatureInstitutionCommandController
     */
    private $featureInstitutionCommandController;

    /**
     * @var AddItemToInstitutionGalleryCommandController
     */
    private $addItemToInstitutionGalleryCommandController;

    /**
     * @var GetInstitutionGalleyQueryController
     */
    private $getInstitutionGalleyQueryController;

    /**
     * @var DeleteInstitutionGalleryAssetCommandController
     */
    private $deleteInstitutionGalleryAssetCommandController;

    /**
     * @var FeatureInstitutionGalleryAssetCommandController
     */
    private $featureInstitutionGalleryAssetCommandController;

    /**
     * @var AddInstitutionAccreditationCommandController
     */
    private $addInstitutionAccreditationCommandController;

    /**
     * @var UpdateInstitutionAccreditationCommandController
     */
    private $updateInstitutionAccreditationCommandController;

    /**
     * @var GetInstitutionAccreditationsCommandController
     */
    private $getInstitutionAccreditationsCommandController;

    /**
     * @var DeleteInstitutionAccreditationCommandController
     */
    private $deleteInstitutionAccreditationCommandController;

    /**
     * @var GetInstitutionAlumniQueryController
     */
    private $getInstitutionAlumniQueryController;

    /**
     * @var AddInstitutionAlumnusCommandController
     */
    private $addInstitutionAlumnusCommandController;

    /**
     * @var GetInstitutionAlumnusDetailsQueryController
     */
    private $getInstitutionAlumnusDetailsQueryController;

    /**
     * @var UpdateInstitutionAlumnusCommandController
     */
    private $updateInstitutionAlumnusCommandController;

    /**
     * @var DeleteInstitutionAlumnusController
     */
    private $deleteInstitutionAlumnusController;

    /**
     * @var GetInstitutionAccreditationDetailsQueryController
     */
    private $getInstitutionAccreditationDetailsQueryController;

    /**
     * @var BuildNewAlumnusRequestCommandController
     */
    private $buildNewAlumnusRequestCommandController;

    /**
     * @var GetInstitutionHighlightsQueryController
     */
    private $getInstitutionHighlightsQueryController;

    /**
     * @var GetInstitutionHighlightDetailsQueryController
     */
    private $getInstitutionHighlightDetailsQueryController;

    /**
     * @var AddInstitutionHighlightCommandController
     */
    private $addInstitutionHighlightCommandController;

    /**
     * @var UpdateInstitutionHighlightCommandController
     */
    private $updateInstitutionHighlightCommandController;

    /**
     * @var DeleteInstitutionHighlightCommandController
     */
    private $deleteInstitutionHighlightCommandController;

    private $publishInstitutionCommandController;

    private $unpublishInstitutionCommandController;

    private $deleteInstitutionCommandController;

    /**
     * @var BulkUploadAlumnusCommandController
     */
    private $bulkUploadAlumnusCommandController;

    private $createQuestionCategoryCommandController;

    private $createQuestionCommandController;

    private $getAlumnusDetailsQueryController;

    private $receiveAlumniQuestionResponseCommandController;

    private $alumnusSubmitReviewCommandController;

    private $updateAlumnusProfileCommandController;

    private $getAlumniListController;

    private $getAlumnusResponsesDetailsQueryController;

    private $getAlumnusReviewDetailsQueryController;

    /**
     * @var GetAlumniQuestionsQueryController
    */
    private $getAlumniQuestionsQueryController;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listInstitutionsController = new ListInstitutionsQueryController($this);
        $this->createInstitutionCommandController = new CreateInstitutionCommandController($this);
        $this->updateInstitutionCommandController = new UpdateInstitutionCommandController($this);
        $this->bulkUploadInstitutionsCommandController = new BulkUploadInstitutionsCommandController($this);
        $this->submitReviewCommandController = new SubmitReviewCommandController($this);
        $this->featureInstitutionCommandController = new FeatureInstitutionCommandController($this);
        $this->addItemToInstitutionGalleryCommandController = new AddItemToInstitutionGalleryCommandController($this);
        $this->getInstitutionGalleyQueryController = new GetInstitutionGalleyQueryController($this);
        $this->deleteInstitutionGalleryAssetCommandController = new DeleteInstitutionGalleryAssetCommandController($this);
        $this->featureInstitutionGalleryAssetCommandController = new FeatureInstitutionGalleryAssetCommandController($this);
        $this->addInstitutionAccreditationCommandController = new AddInstitutionAccreditationCommandController($this);
        $this->updateInstitutionAccreditationCommandController = new UpdateInstitutionAccreditationCommandController($this);
        $this->getInstitutionAccreditationsCommandController = new GetInstitutionAccreditationsCommandController($this);
        $this->deleteInstitutionAccreditationCommandController = new DeleteInstitutionAccreditationCommandController($this);
        $this->addInstitutionAlumnusCommandController = new AddInstitutionAlumnusCommandController($this);
        $this->getInstitutionAlumniQueryController = new GetInstitutionAlumniQueryController($this);
        $this->getInstitutionAlumnusDetailsQueryController = new GetInstitutionAlumnusDetailsQueryController($this);
        $this->updateInstitutionAlumnusCommandController = new UpdateInstitutionAlumnusCommandController($this);
        $this->deleteInstitutionAlumnusController = new DeleteInstitutionAlumnusController($this);
        $this->getInstitutionAccreditationDetailsQueryController = new GetInstitutionAccreditationDetailsQueryController($this);
        $this->buildNewAlumnusRequestCommandController = new BuildNewAlumnusRequestCommandController($this);
        $this->getInstitutionHighlightsQueryController = new GetInstitutionHighlightsQueryController($this);
        $this->getInstitutionHighlightDetailsQueryController = new GetInstitutionHighlightDetailsQueryController($this);
        $this->addInstitutionHighlightCommandController = new AddInstitutionHighlightCommandController($this);
        $this->updateInstitutionHighlightCommandController = new UpdateInstitutionHighlightCommandController($this);
        $this->deleteInstitutionHighlightCommandController = new DeleteInstitutionHighlightCommandController($this);
        $this->publishInstitutionCommandController = new PublishInstitutionCommandController($this);
        $this->unpublishInstitutionCommandController = new UnpublishInstitutionCommandController($this);
        $this->deleteInstitutionCommandController = new DeleteInstitutionCommandController($this);
        $this->bulkUploadAlumnusCommandController = new BulkUploadAlumnusCommandController($this);
        $this->createQuestionCategoryCommandController = new CreateQuestionCategoryCommandController($this);
        $this->createQuestionCommandController = new CreateQuestionCommandController($this);
        $this->getAlumnusDetailsQueryController = new GetAlumnusDetailsQueryController($this);
        $this->alumnusSubmitReviewCommandController = new ReceiveAlumniReviewCommandController($this);
        $this->receiveAlumniQuestionResponseCommandController = new ReceiveAlumniQuestionResponseCommandController($this);
        $this->updateAlumnusProfileCommandController = new UpdateAlumnusProfileCommandController($this);
        $this->getAlumniListController = new GetAlumniListController($this);
        $this->getAlumnusResponsesDetailsQueryController = new GetAlumnusResponsesDetailsQueryController($this);
        $this->getAlumnusReviewDetailsQueryController  = new GetAlumnusReviewDetailsQueryController($this);
        $this->getAlumniQuestionsQueryController = new GetAlumniQuestionsQueryController($this);
    }

    /**
     * Build
     */
    public function build(): JsonResponse
    {
        return $this->respondInJSON((new CraydelJSONResponseType(
            true,
            LanguageTranslationHelper::translate('institutions.success.built'), [
                'countries' => CountryHelper::countries(),
                'institution_type' => InstitutionTypeHelper::types(),
                'ownership_type' => [
                    'Private',
                    'Public',
                    'Region Sponsored',
                    'Community Sponsored',
                    'State/Country'
                ]
            ]
        )));
    }

    /**
     * List institutions
     * @param Request $request
     * @return JsonResponse
     */
    public function institutions(Request $request): JsonResponse
    {
        return $this->listInstitutionsController->getInstitutions($request);
    }

    /**
     * Create an institution
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return $this->createInstitutionCommandController->create($request);
    }

    /**
     * Create an institution
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function bulkUpload(Request $request): JsonResponse
    {
        return $this->bulkUploadInstitutionsCommandController->upload($request);
    }

    /**
     * Create an institution
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $accreditation_id
     * @return JsonResponse
     */
    public function update(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->updateInstitutionCommandController->update(
            $institution_code,
            $request
        );
    }

    /**
     * Review the institution
     * @param string|null $institution_code
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function review(?string $institution_code, Request $request): JsonResponse
    {
        return $this->submitReviewCommandController->review($institution_code, $request);
    }

    /**
     * Edit the institution details
     *
     * @param string|null $institution_code
     *
     * @return JsonResponse
     */
    public function edit(?string $institution_code): JsonResponse
    {
        if (empty($institution_code)) {
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('institutions.errors.invalid_institution_code'),
            )));
        }

        return $this->respondInJSON((new CraydelJSONResponseType(
            true,
            LanguageTranslationHelper::translate('institutions.success.listed'),
            Institution::where('institution_code', trim($institution_code))->first()
        )));
    }

    /**
     * Upload the logo to CDN
     *
     * @param string $institution_code
     * @param string $image_path
     *
     * @return void
     */
    public static function processLogoImage(string $institution_code, string $image_path)
    {
        try {
            if (empty($institution_code)) {
                throw new Exception('Invalid institution Code');
            }

            self::uploadImage(
                $image_path,
                Institution::where('institution_code', $institution_code)->first(), [
                    [
                        'column' => 'logo_url',
                        'width' => 860,
                        'height' => 367
                    ],
                    [
                        'column' => 'logo_url_small',
                        'width' => 430,
                        'height' => 134
                    ]
                ]
            );

            event(new InstitutionLogoImageUploadedEvent($institution_code));

        } catch (Exception $exception) {
            (new self())->logException($exception);
        }
    }

    /**
     * Upload the institution gallery image uploaded
     *
     * @param string|null $asset_code
     * @param string|null $image_path
     *
     * @return void
     * @throws Exception
     */
    public static function processGalleryImage(?string $asset_code, ?string $image_path)
    {
        try {
            if (empty($asset_code)) {
                throw new Exception('Invalid asset Code');
            }

            self::uploadImage(
                $image_path,
                InstitutionGallery::where('asset_code', $asset_code)->first(),
                [
                    [
                        'column' => 'big_image_url',
                        'width' => config('craydle.images.institution_gallery.institution_gallery_image_width_big'),
                        'height' => config('craydle.images.institution_gallery.institution_gallery_image_height_big')
                    ],
                    [
                        'column' => 'small_image_url',
                        'width' => config('craydle.images.institution_gallery.institution_gallery_image_width_small'),
                        'height' => config('craydle.images.institution_gallery.institution_gallery_image_height_small')
                    ]
                ],
                'temp_image_path'
            );

            event(new InstitutionGalleryImageUploadedEvent($asset_code));
        } catch (Exception $exception) {
            (new self())->logException($exception);
        }
    }

    /**
     * Upload the institution accreditation image uploaded
     *
     * @param int|null $accreditation_id
     * @param string|null $temp_image_path
     *
     * @return void
     * @throws Exception
     */
    public static function processAccreditationImage(?int $accreditation_id, ?string $temp_image_path)
    {
        try {
            if (empty($accreditation_id)) {
                throw new Exception('Invalid institution accreditation');
            }

            self::uploadImage(
                $temp_image_path,
                InstitutionAccreditation::where('id', $accreditation_id)->first(),
                [
                    [
                        'column' => 'big_organization_image',
                        'width' => config('craydle.images.institution_gallery.institution_gallery_image_width_big'),
                        'height' => config('craydle.images.institution_gallery.institution_gallery_image_height_big')
                    ],
                    [
                        'column' => 'small_organization_image',
                        'width' => config('craydle.images.institution_gallery.institution_gallery_image_width_small'),
                        'height' => config('craydle.images.institution_gallery.institution_gallery_image_height_small')
                    ]
                ],
                'temp_image_path'
            );
        } catch (Exception $exception) {
            (new self())->logException($exception);
        }
    }

    /**
     * Upload the institution alumnus image uploaded
     *
     * @param int|null $alumnus_id
     * @param string|null $temp_image_path
     *
     * @return void
     * @throws Exception
     */
    public static function processAlumnusImage(?int $alumnus_id, ?string $temp_image_path)
    {
        try {
            if (empty($alumnus_id)) {
                throw new Exception('Invalid institution alumnus ID');
            }

            self::uploadImage(
                $temp_image_path,
                InstitutionAlumnus::where('id', $alumnus_id)->first(),
                [
                    [
                        'column' => 'big_alumnus_image_path',
                        'width' => config('craydle.images.institution_gallery.institution_gallery_image_width_big'),
                        'height' => config('craydle.images.institution_gallery.institution_gallery_image_height_big')
                    ],
                    [
                        'column' => 'small_alumnus_image_path',
                        'width' => config('craydle.images.institution_gallery.institution_gallery_image_width_small'),
                        'height' => config('craydle.images.institution_gallery.institution_gallery_image_height_small')
                    ]
                ],
                'temp_image_path'
            );
        } catch (Exception $exception) {
            (new self())->logException($exception);
        }
    }

    /**
     * Delete the institution logo images from CDN
     *
     * @param string $institution_code
     *
     * @return void
     * @throws Exception
     */
    public static function deleteInstitutionLogoImages(string $institution_code)
    {
        if (empty($institution_code)) {
            throw new Exception('Invalid institution Code');
        }

        $institution = DB::table((new Institution())->getTable())
            ->where('institution_code', trim($institution_code))
            ->first([
                'logo_url',
                'logo_url_small'
            ]);

        if (isset($institution->logo_url) && !empty($institution->logo_url)) {
            self::_deleteFromCDN($institution->logo_url);
        }

        if (isset($institution->logo_url_small) && !empty($institution->logo_url_small)) {
            self::_deleteFromCDN($institution->logo_url_small);
        }

        dispatch((new UploadInstitutionLogoToCDNJob($institution_code)))
            ->onQueue('upload_images_to_cdn');
    }

    /**
     * Delete images
     *
     * @param array|null $images
     * @throws Exception
     */
    public static function deleteFromCDN(?array $images)
    {
        if (!is_array($images)) {
            throw new Exception("Invalid image list");
        }

        if (count($images) <= 0) {
            throw new Exception("Empty image list");
        }

        foreach ($images as $key => $image) {
            if (!empty($image)) {
                self::_deleteFromCDN($image);
            }
        }
    }

    /**
     * Feature/un-feature the institution
     *
     * @param Request $request
     * @param string|null $institution_code
     * @return JsonResponse
     */
    public function feature(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->featureInstitutionCommandController->feature($request, $institution_code);
    }

    /**
     * Add item to institution gallery
     * @param Request $request
     * @param string|null $institution_code
     *
     * @return JsonResponse
     */
    public function addGalleryItem(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->addItemToInstitutionGalleryCommandController->add($request, $institution_code);
    }

    /**
     * Get the institution gallery
     */
    public function getInstitutionGallery(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->getInstitutionGalleyQueryController->getInstitutionGallery($institution_code);
    }

    /**
     * Delete gallery asset
     */
    public function deleteGalleryItem(Request $request, ?string $institution_code, ?string $asset_code): JsonResponse
    {
        return $this->deleteInstitutionGalleryAssetCommandController->delete($request, $institution_code, $asset_code);
    }

    /**
     * Feature institution gallery asset
     */
    public function featureGalleryItem(Request $request, ?string $institution_code, ?string $asset_code): JsonResponse
    {
        return $this->featureInstitutionGalleryAssetCommandController->feature($request, $institution_code, $asset_code);
    }

    /**
     * Get institution accreditations
     *
     * @param Request $request
     * @param string|null $institution_code
     * @return JsonResponse
     */
    public function getAccreditations(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->getInstitutionAccreditationsCommandController->get($request, $institution_code);
    }

    /**
     * Get the accreditation  details
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $accreditation_id
     * @return JsonResponse
     */
    public function getAccreditation(Request $request, ?string $institution_code, ?int $accreditation_id): JsonResponse
    {
        return $this->getInstitutionAccreditationDetailsQueryController->accreditation(
            $request,
            $institution_code,
            $accreditation_id
        );
    }

    /**
     * Add institution accreditation
     *
     * @param Request $request
     * @param string|null $institution_code
     * @return JsonResponse
     */
    public function addAccreditation(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->addInstitutionAccreditationCommandController->add($request, $institution_code);
    }

    /**
     * Update institution accreditation
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $accreditation_id
     * @return JsonResponse
     */
    public function updateAccreditation(Request $request, ?string $institution_code, ?int $accreditation_id): JsonResponse
    {
        return $this->updateInstitutionAccreditationCommandController->update(
            $request,
            $institution_code,
            $accreditation_id
        );
    }

    /**
     * Delete institution accreditation
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $accreditation_id
     * @return JsonResponse
     */
    public function deleteAccreditation(Request $request, ?string $institution_code, ?int $accreditation_id): JsonResponse
    {
        return $this->deleteInstitutionAccreditationCommandController->delete(
            $request,
            $institution_code,
            $accreditation_id
        );
    }

    /**
     * Get institution accreditation images
     *
     * @param int|null $accreditation_id
     * @return array
     * @throws Exception
     */
    public static function getInstitutionAccreditationImages(?int $accreditation_id): array
    {
        if (empty($accreditation_id)) {
            throw new Exception("Invalid accreditation ID");
        }

        $accreditation = DB::table((new InstitutionAccreditation())->getTable())
            ->where('id', $accreditation_id)
            ->get([
                'big_organization_image',
                'small_organization_image'
            ]);

        return call_user_func(function () use ($accreditation) {
            $_list = [];

            foreach ($accreditation as $key => $value) {
                if (!empty($value)) {
                    array_push($_list, $value);
                }
            }

            return $_list;
        });
    }

    /**
     * Get institution alumnus images
     *
     * @param int|null $alumnus_id
     * @return array
     * @throws Exception
     */
    public static function getInstitutionAlumnusImages(?int $alumnus_id): array
    {
        if (empty($alumnus_id)) {
            throw new Exception("Invalid alumnus ID");
        }

        $alumnus = DB::table((new InstitutionAlumnus())->getTable())
            ->where('id', $alumnus_id)
            ->get([
                'big_alumnus_image_path',
                'small_alumnus_image_path'
            ]);

        return call_user_func(function () use ($alumnus) {
            $_list = [];

            foreach ($alumnus as $key => $value) {
                if (!empty($value)) {
                    array_push($_list, $value);
                }
            }

            return $_list;
        });
    }

    /**
     * Build a new alumnus request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function buildAlumnus(Request $request): JsonResponse
    {
        return $this->buildNewAlumnusRequestCommandController->build($request);
    }

    /**
     * Get institution accreditations
     *
     * @param Request $request
     * @param string|null $institution_code
     * @return JsonResponse
     */
    public function getAlumni(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->getInstitutionAlumniQueryController->get($request, $institution_code);
    }

    /**
     * Add institution alumnus
     *
     * @param Request $request
     * @param string|null $institution_code
     * @return JsonResponse
     */
    public function addAlumnus(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->addInstitutionAlumnusCommandController->add($request, $institution_code);
    }

    /**
     * Update institution alumnus
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $alumnus_id
     * @return JsonResponse
     */
    public function updateAlumnus(Request $request, ?string $institution_code, ?int $alumnus_id): JsonResponse
    {
        return $this->updateInstitutionAlumnusCommandController->update(
            $request,
            $institution_code,
            $alumnus_id
        );
    }

    /**
     * Get institution alumnus details
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $alumnus_id
     * @return JsonResponse
     */
    public function getAlumnus(Request $request, ?string $institution_code, ?int $alumnus_id): JsonResponse
    {
        return $this->getInstitutionAlumnusDetailsQueryController->alumnus($request, $institution_code, $alumnus_id);
    }

    /**
     * Delete alumnus
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $alumnus_id
     * @return JsonResponse
     */
    public function deleteAlumnus(Request $request, ?string $institution_code, ?int $alumnus_id): JsonResponse
    {
        return $this->deleteInstitutionAlumnusController->delete(
            $request,
            $institution_code,
            $alumnus_id
        );
    }

    /**
     * Get the institutions key highlights
     */
    public function highlights(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->getInstitutionHighlightsQueryController->get($request, $institution_code);
    }

    /**
     * Get the institution highlight details
     *
     */
    public function highlight(Request $request, ?string $institution_code, ?int $highlight_id): JsonResponse
    {
        return $this->getInstitutionHighlightDetailsQueryController->get($request, $institution_code, $highlight_id);
    }

    /**
     * Adding institution highlight
     */
    public function addHighlight(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->addInstitutionHighlightCommandController->add($request, $institution_code);
    }

    /**
     * Update the institution highlight details
     */
    public function updateHighlight(Request $request, ?string $institution_code, ?int $highlight_id): JsonResponse
    {
        return $this->updateInstitutionHighlightCommandController->update($request, $institution_code, $highlight_id);
    }

    /**
     * Delete the institution highlight
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $highlight_id
     * @return JsonResponse
     */
    public function deleteHighlight(Request $request, ?string $institution_code, ?int $highlight_id): JsonResponse
    {
        return $this->deleteInstitutionHighlightCommandController->delete($request, $institution_code, $highlight_id);
    }

    public function publish(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->publishInstitutionCommandController->publish($request, $institution_code);
    }

    public function unpublish(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->unpublishInstitutionCommandController->unpublish($request, $institution_code);
    }

    public function delete(Request $request, ?string $institution_code): JsonResponse
    {
        return $this->deleteInstitutionCommandController->delete($request, $institution_code);
    }

    public function bulkAlumniUpload(Request $request): JsonResponse
    {
        return $this->bulkUploadAlumnusCommandController->upload($request);
    }

    public function createQuestionCategory(Request $request): JsonResponse
    {
        return $this->createQuestionCategoryCommandController->create($request);
    }

    public function createQuestion(Request $request): JsonResponse
    {
        return $this->createQuestionCommandController->create($request);
    }

    public function getAlumniBySlug(Request $request): JsonResponse
    {
        return $this->getAlumnusDetailsQueryController->getAlumnus($request);
    }

    public function submitQuestionRespond(Request $request): JsonResponse
    {
        return $this->receiveAlumniQuestionResponseCommandController->process($request);
    }

    public function alumniSubmitReview(Request $request): JsonResponse
    {
        return $this->alumnusSubmitReviewCommandController->submit($request);
    }

    public function updateAlumniProfile(Request $request, $alumni_id): JsonResponse
    {
        return $this->updateAlumnusProfileCommandController->updateProfile($request, $alumni_id);
    }

    public function getAlumniList(Request $request): JsonResponse
    {
        return $this->getAlumniListController->alumniList($request);
    }

    public function getAlumniResponses(Request $request): JsonResponse
    {
        return $this->getAlumnusResponsesDetailsQueryController->get($request);
    }

    public function getAlumniReviews(Request $request): JsonResponse
    {
        return $this->getAlumnusReviewDetailsQueryController->get($request);
    }

    public function getAlumniQuestions(Request $request, $alumni_id): JsonResponse{
        return $this->getAlumniQuestionsQueryController->get($alumni_id);
    }
}
