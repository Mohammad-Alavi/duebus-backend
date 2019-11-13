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
        $message->is_read = false;

        $message->save();

        //  Update updated_at of session
        SessionRepository::updateUpdatedAtTimestamp($sessionId);

        return $message;
    }


    static public function getFirstMessage(int $sessionId)
    {
        $query = Message::query();
        $query->where('session_id', '=', $sessionId)
            ->orderBy('id');
        return $query->first();
    }

    static public function getLastMessage(int $sessionId)
    {
        $query = Message::query();
        $query->where('session_id', '=', $sessionId)
            ->orderByDesc('id');
        return $query->first();
    }

    static public function getInquiryMessages(int $sessionId)
    {
        $query = Message::query();
        $query->where('session_id', '=', $sessionId);
        $query->whereNotNull('title');
        return $query->get();
    }

    /**
     *
     * @param int $page
     * @param int $userId
     * @param int $sessionId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    static public function paginate(int $page, int $userId, int $sessionId)
    {
        self::readAllMessages($userId, $sessionId);

        $query = Message::query();
        $query->where('session_id', $sessionId);
        $query->orderByDesc('created_at');

        return $query->paginate(20, $page);
    }

    static private function readAllMessages(int $userId, int $sessionId)
    {
        $query = Message::query();
        $query->where('sender_id', '!=', $userId);
        $query->where('session_id', $sessionId);
        $query->where('is_read', false);
        $query->update(['is_read' => true]);
    }

    static public function countUnreadMessages(int $userId, $sessionId = null)
    {
        $query = Message::query();
        $query->where('sender_id', '!=', $userId);
        if ($sessionId !== null) $query->where('session_id', $sessionId);
        $query->where('is_read', false);

        return $query->count();
    }

}
