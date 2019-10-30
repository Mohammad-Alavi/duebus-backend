<?php namespace Denora\Duebusbusiness\Classes\Repositories;

use Carbon\Carbon;
use Denora\Duebusprofile\Classes\Repositories\EntrepreneurRepository;
use Denora\Duebusprofile\Models\UserBookmark;

class AnalyzeEntrepreneurRepository
{

    static public function getAnalyzedData($entrepreneur)
    {

        return [
            'bookmark' => [
                'week' => self::getBookmarkAnalytics($entrepreneur, 7),
                'month' => self::getBookmarkAnalytics($entrepreneur, 30),
            ],
        ];
    }

    static private function getBookmarkAnalytics($entrepreneur, int $days)
    {
        $today = Carbon::today();

        $array = [];

        for ($i = 0; $i < $days; $i++) {

            $date = $today->toDate();

            $businessIds = (new EntrepreneurRepository())->getOwnedBusinessIds($entrepreneur->id);
            $count = UserBookmark::query()
                ->whereIn('business_id', $businessIds)
                ->whereYear('created_at', $today->year)
                ->whereMonth('created_at', $today->month)
                ->whereDay('created_at', $today->day)
                ->count();

            array_push($array, [
                'date' => $date,
                'count' => $count
            ]);
            $today->subDay(1);
        }

        return $array;
    }
}
