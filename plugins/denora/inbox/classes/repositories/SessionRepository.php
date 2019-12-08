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

    static public function createSession(int $senderId, int $receiverId, int $businessId, string $type, $preferredDate, $preferredTime)
    {
        $session = new Session();
        $session->sender_id = $senderId;
        $session->receiver_id = $receiverId;
        $session->business_id = $businessId;
        $session->type = $type;
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

    /**
     *
     * @param int $page
     * @param int $userId
     * @param null $type
     * @param null $business_id
     * @param null $isRead
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    static public function paginate(int $page, int $userId, $type = null, $business_id = null, $isRead = null)
    {
        $query = Session::query();
        $query->where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId);
        });
        if ($type) $query->where('type', $type);
        if ($business_id) $query->where('business_id', $business_id);
        if ($isRead !== null)
            $query->whereHas('messages', function ($q) use ($userId, $isRead) {
                $q->where('is_read', $isRead);
            });

        $query->orderByDesc('updated_at');

        return $query->paginate(10, $page);
    }

}
