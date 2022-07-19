<?php
namespace App\Http\Controllers\CraydelTypes;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;

class MailingListSubscriberType
{
    /**
     * @var string|null $subscriber_name
    */
    protected ?string $subscriber_name;

    /**
     * @var string|null $subscriber_email
    */
    protected ?string $subscriber_email;

    /**
     * @var string|null $subscriber_mobile_number
    */
    protected ?string $subscriber_mobile_number;

    /**
     * @return string|null
     */
    public function getSubscriberName(): ?string
    {
        return $this->subscriber_name;
    }

    /**
     * @param string|null $subscriber_name
     * @return MailingListSubscriberType
     */
    public function setSubscriberName(?string $subscriber_name): MailingListSubscriberType
    {
        $this->subscriber_name = !CraydelHelperFunctions::isNull($subscriber_name) ? CraydelHelperFunctions::toCleanString($subscriber_name) : "";
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubscriberEmail(): ?string
    {
        return $this->subscriber_email;
    }

    /**
     * @param string|null $subscriber_email
     * @return MailingListSubscriberType
     */
    public function setSubscriberEmail(?string $subscriber_email): MailingListSubscriberType
    {
        $this->subscriber_email = CraydelHelperFunctions::isEmail($subscriber_email) ? CraydelHelperFunctions::toEmailAddress($subscriber_email) : "";
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubscriberMobileNumber(): ?string
    {
        return $this->subscriber_mobile_number;
    }

    /**
     * @param string|null $subscriber_mobile_number
     * @return MailingListSubscriberType
     */
    public function setSubscriberMobileNumber(?string $subscriber_mobile_number): MailingListSubscriberType
    {
        $this->subscriber_mobile_number = $subscriber_mobile_number;
        return $this;
    }
}
