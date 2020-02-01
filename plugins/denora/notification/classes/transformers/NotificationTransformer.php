<?php namespace Denora\Notification\Classes\Transformers;

use Denora\Duebus\Classes\Repositories\PackageRepository;
use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusprofile\Classes\Repositories\UserRepository;
use Denora\Notification\Classes\BaseEvent;
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
            'doer_name' => self::getDoerName($notification),
            'object_type' => $notification->object_type,
            'object_id' => $notification->object_id,
            'object_name' => self::getObjectName($notification),
            'cost' => $notification->cost,

            'created_at' => $notification->created_at,
            'updated_at' => $notification->updated_at,
        ];

    }

    static private function getDoerName(Notification $notification){
        if ($notification->doer_id){
            $doerUser = (new UserRepository())->findById($notification->doer_id);
            return $doerUser->name . ' ' . $doerUser->surname;
        }

        return null;
    }

    static private function getObjectName(Notification $notification){
        if (!$notification->object_type || !$notification->object_id) return null;

        if ($notification->object_type == BaseEvent::$OBJECT_TYPE_BUSINESS){
            $business = (new BusinessRepository())->findById($notification->object_id, true);
            return $business->name;
        }

        //  This is not needed cuz package_id is not passed to notification event class
//        if ($notification->object_type == BaseEvent::$OBJECT_TYPE_WALLET){
//            $package = (new PackageRepository())->findById($notification->object_id);
//            return $package->name;
//        }

        return null;
    }
}
