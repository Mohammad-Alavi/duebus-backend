<?php namespace Denora\Inbox\Classes\Transformers;

use Denora\Duebusprofile\Classes\Repositories\UserRepository;
use Denora\Duebusprofile\Classes\Transformers\UserTransformer;
use Denora\Inbox\Classes\Repositories\MessageRepository;
use Denora\Inbox\Models\Session;

class SessionTransformer
{

    /**
     * @param Session $session
     *
     * @param $user
     * @return array
     */
    static function transform($session, $user)
    {
        return [
            'id' => $session->id,
            'sender' => UserTransformer::transform(
                (new UserRepository())->findById($session->sender_id)
            ),
            'receiver' => UserTransformer::transform(
                (new UserRepository())->findById($session->receiver_id)
            ),
            'type' => $session->type,
            'is_read' => self::getIsRead($session, $user),

            'last_message' => MessageTransformer::transform(MessageRepository::getLastMessage($session->id)),

            'preferred_date' => $session->preferred_date,
            'preferred_time' => $session->preferred_time,
            'created_at' => $session->created_at,
            'updated_at' => $session->updated_at,
        ];

    }

    private static function getIsRead($session, $user): bool {
        if ($user->id == $session->sender_id) return $session->is_read_by_sender;
        if ($user->id == $session->receiver_id) return $session->is_read_by_receiver;
        return null;
    }

}
