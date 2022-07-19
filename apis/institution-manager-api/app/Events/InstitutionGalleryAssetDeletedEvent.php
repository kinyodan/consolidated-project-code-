<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;

class InstitutionGalleryAssetDeletedEvent extends Event
{
    use SerializesModels;

    /**
     * @var object $asset
    */
    public $asset;

    /**
     * Create a new event instance.
     *
     * @param object|null $asset_code
     */
    public function __construct(?object $asset_code)
    {
        $this->asset = $asset_code;
    }
}
