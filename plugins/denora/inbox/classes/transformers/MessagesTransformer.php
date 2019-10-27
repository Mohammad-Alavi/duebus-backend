<?php namespace Denora\Inbox\Classes\Transformers;

use Denora\Inbox\Models\Message;

class MessagesTransformer
{

    /**
     * @param Message[] $messages
     *
     * @return array
     */
    static function transform($messages)
    {
        $array = [];

        if ($messages == null) return $array;

        foreach ($messages as $message) {
            array_push($array, MessageTransformer::transform($message));
        }

        return $array;
    }

}
