<?php namespace Denora\Notification\Classes\Events;

use Denora\Notification\Classes\BaseEvent;

class BusinessRevealedEvent extends BaseEvent
{
    public function getActionType()
    {
        return BaseEvent::$ACTION_TYPE_BUSINESS_REVEALED;
    }

    public function __construct(int $receiverUserId, int $businessId)
    {
        $this->objectType = BaseEvent::$OBJECT_TYPE_BUSINESS;
        $this->objectId = $businessId;
        parent::__construct($receiverUserId);
    }
}
