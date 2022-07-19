<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;

class InstitutionLogoImageUploadedEvent extends Event
{
    use SerializesModels;

    /**
     * @var string $institution_code
    */
    public $institution_code;

    /**
     * Create a new event instance.
     *
     * @param string|null $institution_code
     */
    public function __construct(?string $institution_code)
    {
        $this->institution_code = $institution_code;
    }
}
