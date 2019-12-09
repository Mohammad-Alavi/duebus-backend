<?php namespace Denora\Duebusprofile\Classes\Transformers;

use Denora\Inbox\Classes\Repositories\MessageRepository;
use RainLab\User\Models\User;

class ProfileTransformer {

    /**
     * @param User $user
     *
     * @return array
     */
    static function transform(User $user) {

        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'surname'      => $user->surname,
            //'username'     => $user->username,
            'email'        => $user->email,
            'avatar'       => $user->avatar ? $user->avatar->path : null,
            'is_activated' => $user->is_activated,
            'point'        => $user->points,
            'point_expires_at'        => $user->point_expires_at,

            'unread_all_messages_count' => MessageRepository::countUnreadMessages($user->id),
            'unread_inquiry_messages_count' => MessageRepository::countUnreadMessages($user->id, null, 'inquiry'),
            'unread_meeting_request_messages_count' => MessageRepository::countUnreadMessages($user->id, null, 'meeting request'),
            //'is_superuser' => $user->is_superuser,

            'entrepreneur_profile'   => $user->entrepreneur ? EntrepreneurTransformer::transform($user->entrepreneur) : null,
            'investor_profile'       => $user->investor ? InvestorTransformer::transform($user->investor) : null,
            'representative_profile' => $user->representative ? RepresentativeTransformer::transform($user->representative) : null,

            'bookmarks_count' => count($user->bookmarked_businesses),

            //'created_at' => $user->created_at,
            //'updated_at' => $user->updated_at,
        ];
    }

}
