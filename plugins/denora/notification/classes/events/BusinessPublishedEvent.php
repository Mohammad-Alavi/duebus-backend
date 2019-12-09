<?php namespace Denora\Notification\Classes\Events;

use Denora\Notification\Classes\BaseEvent;

class BusinessPublishedEvent extends BaseEvent
{
    public function getActionType()
    {
        return BaseEvent::$ACTION_TYPE_BUSINESS_PUBLISHED;
    }

    public function __construct(int $receiverUserId, int $businessId, float $price)
    {
        $this->objectType = BaseEvent::$OBJECT_TYPE_BUSINESS;
        $this->objectId = $businessId;
        $this->cost = $price;
        parent::__construct($receiverUserId);
    }
}
