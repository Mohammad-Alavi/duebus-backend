<?php namespace Denora\Inbox\Classes\Repositories;

use Denora\Inbox\Models\Session;

class SessionRepository
{

    static public function find(int $senderId, int $receiverId, int $businessId, string $type)
    {
        $query = Session::query();
        $query->where('sender_id', '=', $senderId)
            ->where('receiver_id', '=', $receiverId)
            ->where('business_id', '=', $businessId)
            ->where('type', '=', $type);
        return $query->first();
    }

    static public function findBySenderId(int $userId)
    {
        $query = Session::query();
        $query->where('sender_id', '=', $userId);
        return $query->get();
    }

    static public function findByReceiverId(int $userId)
    {
        $query = Session::query();
        $query->where('receiver_id', '=', $userId);
        return $query->get();
    }

    static public function countUnreadSessions($userId){
        $query = Session::query();
        $unreadAsSender = $query->where('sender_id', '=', $userId)->where('is_read_by_sender', '=', false)->count();
        $unreadAsReceiver = $query->where('receiver_id', '=', $userId)->where('is_read_by_receiver', '=', false)->count();
        return $unreadAsSender + $unreadAsReceiver;

    }

    static public function createSession(int $senderId, int $receiverId, int $businessId, string $type, $preferredDate, $preferredTime)
    {
        $session = new Session();
        $session->sender_id = $senderId;
        $session->receiver_id = $receiverId;
        $session->business_id = $businessId;
        $session->type = $type;
        $session->is_read_by_sender = true;
        $session->is_read_by_receiver = false;
        $session->preferred_date = $preferredDate;
        $session->preferred_time = $preferredTime;

        $session->save();

        return $session;
    }

    static public function updateUpdatedAtTimestamp(int $session_id)
    {
        $session = self::findById($session_id);
        $session->updated_at = now();
        $session->save();
        return $session;
    }

    /**
     * @param int $sessionId
     *
     * @return Session
     */
    static public function findById(int $sessionId)
    {
        return Session::find($sessionId);
    }

    static public function updateIsReadOnAdd(int $userId, int $session_id)
    {
        $session = self::findById($session_id);
        if ($userId == $session->sender_id) $session->is_read_by_receiver = false;
        if ($userId == $session->receiver_id) $session->is_read_by_sender = false;
        $session->save();
        return $session;
    }

    static public function updateIsReadOnGet(int $userId, int $session_id)
    {
        $session = self::findById($session_id);
        if ($userId == $session->sender_id) $session->is_read_by_sender = true;
        if ($userId == $session->receiver_id) $session->is_read_by_receiver = true;
        $session->save();
        return $session;
    }

    /**
     *
     * @param int $page
     * @param int $userId
     * @param null $type
     * @param null $business_id
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    static public function paginate(int $page, int $userId, $type = null, $business_id = null)
    {
        $query = Session::query();
        $query->where('sender_id', '=', $userId)->orWhere('receiver_id', '=', $userId);
        if ($type) $query->where('type', '=', $type);
        if ($business_id) $query->where('business_id', '=', $business_id);
        $query->orderByDesc('updated_at');

        return $query->paginate(20, $page);
    }

}
