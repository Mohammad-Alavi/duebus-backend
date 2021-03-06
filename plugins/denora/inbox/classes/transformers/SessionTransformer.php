<?php namespace Denora\Inbox\Classes\Transformers;

use Denora\Duebusbusiness\Classes\Transformers\BusinessTransformer;
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
            'business' => BusinessTransformer::transform($session->business),
            'type' => $session->type,
            'unread_messages_count' => MessageRepository::countUnreadMessages($user->id, $session->id),

            'first_message' => MessageTransformer::transform(MessageRepository::getFirstMessage($session->id)),
            'last_message' => MessageTransformer::transform(MessageRepository::getLastMessage($session->id)),
            'inquiry_messages' => MessagesTransformer::transform(MessageRepository::getInquiryMessages($session->id)),

            'preferred_date' => $session->preferred_date,
            'preferred_time' => $session->preferred_time,
            'created_at' => $session->created_at,
            'updated_at' => $session->updated_at,
        ];

    }
}
