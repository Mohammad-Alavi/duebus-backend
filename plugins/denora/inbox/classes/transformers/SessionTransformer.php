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
     * @return array
     */
    static function transform($session)
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
            'is_read' => (bool)$session->is_read,

            'last_message' => MessageTransformer::transform(MessageRepository::getLastMessage($session->id)),

            'preferred_date' => $session->preferred_date,
            'created_at' => $session->created_at,
            'updated_at' => $session->updated_at,
        ];

    }

}
