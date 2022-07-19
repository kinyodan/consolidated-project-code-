<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;

class InstitutionUpdatedEvent extends Event
{
    use SerializesModels;

    /**
     * @var string $institution_code
    */
    public $institution_code;

    /**
     * Create a new event instance.
     *
     * @param string $institution_code
     *
     * @return void
     */
    public function __construct(string $institution_code)
    {
        $this->institution_code = $institution_code;
    }
}
