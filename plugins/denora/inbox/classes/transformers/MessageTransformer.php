<?php namespace Denora\Inbox\Classes\Transformers;

use Denora\Duebusprofile\Classes\Repositories\UserRepository;
use Denora\Duebusprofile\Classes\Transformers\UserTransformer;
use Denora\Inbox\Models\Message;

class MessageTransformer
{

    /**
     * @param Message $message
     *
     * @return array
     */
    static function transform($message)
    {
        return [
            'id' => $message->id,
            'sender' => UserTransformer::transform(
                (new UserRepository())->findById($message->sender_id)
            ),
            'title' => $message->title,
            'text' => $message->text,

            'created_at' => $message->created_at,
        ];

    }

}
