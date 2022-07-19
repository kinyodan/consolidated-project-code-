<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;

class InstitutionGalleryImageUploadedEvent extends Event
{
    use SerializesModels;

    /**
     * @var string $asset_code
    */
    public $asset_code;

    /**
     * Create a new event instance.
     *
     * @param string|null $asset_code
     */
    public function __construct(?string $asset_code)
    {
        $this->asset_code = $asset_code;
    }
}
