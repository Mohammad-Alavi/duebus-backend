<?php namespace Denora\Notification\Classes\Events;

use Denora\Notification\Classes\BaseEvent;

class WalletChargedEvent extends BaseEvent
{
    public function getActionType()
    {
        return BaseEvent::$ACTION_TYPE_WALLET_CHARGED;
    }

    public function __construct(int $receiverUserId, $package)
    {
        $this->objectType = BaseEvent::$OBJECT_TYPE_WALLET;
        $this->cost = $package->points;
        parent::__construct($receiverUserId);
    }
}
