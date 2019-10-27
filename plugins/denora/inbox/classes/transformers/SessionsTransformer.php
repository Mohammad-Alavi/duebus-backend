<?php namespace Denora\Inbox\Classes\Transformers;

use Denora\Inbox\Models\Session;

class SessionsTransformer
{

    /**
     * @param Session[] $sessions
     *
     * @return array
     */
    static function transform($sessions)
    {
        $array = [];

        if ($sessions == null) return $array;

        foreach ($sessions as $session) {
            array_push($array, SessionTransformer::transform($session));
        }

        return $array;
    }

}
