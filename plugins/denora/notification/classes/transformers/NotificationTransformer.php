<?php namespace Denora\Notification\Classes\Transformers;

use Denora\Notification\Models\Notification;

class NotificationTransformer
{

    /**
     * @param Notification $notification
     *
     * @return array
     */
    static function transform($notification)
    {
        return [
            'id' => $notification->id,
            'action_type' => $notification->action_type,
            'doer_type' => $notification->doer_type,
            'doer_id' => $notification->doer_id,
            'object_type' => $notification->object_type,
            'object_id' => $notification->object_id,
            'cost' => $notification->cost,

            'created_at' => $notification->created_at,
            'updated_at' => $notification->updated_at,
        ];

    }
}
