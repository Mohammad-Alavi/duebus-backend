<?php namespace Denora\Notification\Classes\Repositories;

use Denora\Notification\Models\Notification;

class NotificationRepository
{
    static function createNotification(int $userId, string $actionType, $doerType, $doerId, $objectType, $objectId, $cost)
    {
        $notification = new Notification();
        $notification->user_id = $userId;
        $notification->action_type = $actionType;
        $notification->doer_type = $doerType;
        $notification->doer_id = $doerId;
        $notification->object_type = $objectType;
        $notification->object_id = $objectId;
        $notification->cost = $cost;

        $notification->save();
    }

    static public function paginate(int $page, int $userId)
    {
        $query = Notification::query();
        $query->where('user_id', $userId);
        $query->orderByDesc('created_at');

        return $query->paginate(10, $page);
    }


}
