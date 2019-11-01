<?php namespace Denora\Notification\Classes\Transformers;

use Denora\Notification\Models\Notification;

class NotificationsTransformer {

    /**
     * @param Notification[] $notifications
     *
     * @return array
     */
    static function transform($notifications) {
        $array = [];

        if ($notifications == null) return $array;

        foreach ($notifications as $notification) {
            array_push($array, NotificationTransformer::transform($notification));
        }

        return $array;
    }

}
