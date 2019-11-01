<?php namespace Denora\Notification\Classes\Channels;

use Denora\Notification\Classes\BaseChannel;
use Denora\Notification\Classes\Repositories\NotificationRepository;

class SaveToDatabaseChannel extends BaseChannel
{

    protected function fire()
    {
        NotificationRepository::createNotification(
            $this->event->receiverUser->id,
            $this->event->getActionType(),
            $this->event->doerType,
            $this->event->doerId,
            $this->event->objectType,
            $this->event->objectId,
            $this->event->cost
        );
    }
}
