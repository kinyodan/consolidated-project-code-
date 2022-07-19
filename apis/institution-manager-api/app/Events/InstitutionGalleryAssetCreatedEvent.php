<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;

class InstitutionGalleryAssetCreatedEvent extends Event
{
    use SerializesModels;

    /**
     * @var string $asset_code
    */
    public $asset_code;

    /**
     * @var string $asset_image_temp_path
    */
    public $asset_image_temp_path;

    /**
     * @var string $type
    */
    public $type;

    /**
     * @var string $institution_code
    */
    public $institution_code;

    /**
     * Create a new event instance.
     *
     * @param string|null $asset_code
     * @param string|null $asset_image_temp_path
     * @param string|null $type
     * @param string|null $institution_code
     */
    public function __construct(?string $asset_code, ?string $asset_image_temp_path, ?string $type = null, ?string $institution_code = null)
    {
        $this->asset_code = $asset_code;
        $this->asset_image_temp_path = $asset_image_temp_path;
        $this->type = $type;
        $this->institution_code = $institution_code;
    }
}
