<?php namespace Denora\Notification\Classes;

use Denora\Duebusprofile\Classes\Repositories\UserRepository;
use Denora\Notification\Classes\Channels\OneSignalChannel;
use Denora\Notification\Classes\Channels\SaveToDatabaseChannel;

abstract class BaseEvent
{
    static $ACTION_TYPE_ENTREPRENEUR_PROFILE_VERIFIED = 'entrepreneur_profile_verified';
    static $ACTION_TYPE_ENTREPRENEUR_PROFILE_UNVERIFIED = 'entrepreneur_profile_unverified';
    static $ACTION_TYPE_INVESTOR_PROFILE_VERIFIED = 'investor_profile_verified';
    static $ACTION_TYPE_INVESTOR_PROFILE_UNVERIFIED = 'investor_profile_unverified';
    static $ACTION_TYPE_BUSINESS_CREATED = 'business_created';
    static $ACTION_TYPE_BUSINESS_PUBLISHED = 'business_published';
    static $ACTION_TYPE_BUSINESS_VIEWED = 'business_viewed';
    static $ACTION_TYPE_BUSINESS_REVEALED = 'business_revealed';
    static $ACTION_TYPE_WALLET_CHARGED = 'wallet_charged';

    static $OBJECT_TYPE_BUSINESS = 'business';
    static $OBJECT_TYPE_WALLET  = 'wallet';

    public $receiverUser;
    public $doerType = null;
    public $doerId = null;
    public $objectType = null;
    public $objectId = null;
    public $cost = null;

    public function __construct(int $receiverUserId)
    {
        $this->receiverUser = (new UserRepository())->findById($receiverUserId);

        new SaveToDatabaseChannel($this);
        new OneSignalChannel($this);

    }

    public abstract function getActionType();

}
