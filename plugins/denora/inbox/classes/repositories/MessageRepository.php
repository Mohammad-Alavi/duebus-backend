<?php namespace Denora\Inbox\Classes\Repositories;

use Denora\Inbox\Models\Message;

class MessageRepository
{

    /**
     * @param int $messageId
     *
     * @return Message
     */
    static public function findById(int $messageId)
    {
        return Message::find($messageId);
    }

    static public function createMessage(int $senderId, int $sessionId, $title, string $text)
    {
        $message = new Message();
        $message->sender_id = $senderId;
        $message->session_id = $sessionId;
        $message->title = $title;
        $message->text = $text;

        $message->save();

        //  Update updated_at of session
        SessionRepository::updateUpdatedAtTimestamp($sessionId);

        //  Make session's is_read false
        SessionRepository::updateIsRead($sessionId, false);
        return $message;
    }

    /**
     * @param int $sessionId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    static public function getLastMessage(int $sessionId){
        $query = Message::query();
        $query->where('session_id', '=', $sessionId)->latest();
        return $query->first();
    }

    /**
     *
     * @param int $page
     * @param int $sessionId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    static public function paginate(int $page, int $sessionId)
    {
        $query = Message::query();
        $query->where('session_id', '=', $sessionId);
        $query->orderByDesc('created_at');

        return $query->paginate(20, $page);
    }

}
