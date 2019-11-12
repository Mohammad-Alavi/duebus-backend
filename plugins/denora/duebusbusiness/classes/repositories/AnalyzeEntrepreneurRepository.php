<?php namespace Denora\Duebusbusiness\Classes\Repositories;

use Carbon\Carbon;
use Denora\Duebusprofile\Classes\Repositories\EntrepreneurRepository;
use Denora\Duebusprofile\Models\InvestorReveal;
use Denora\Duebusprofile\Models\InvestorView;
use Denora\Duebusprofile\Models\UserBookmark;

class AnalyzeEntrepreneurRepository
{

    static public function getAnalyzedData($entrepreneur)
    {

        return [
            'bookmark' => [
                'week' => self:: getAnalytics($entrepreneur, 'bookmark', 7),
                'month' => self::getAnalytics($entrepreneur, 'bookmark', 30),
            ],
            'view' => [
                'week' => self:: getAnalytics($entrepreneur, 'view', 7),
                'month' => self::getAnalytics($entrepreneur, 'view', 30),
            ],
            'reveal' => [
                'week' => self:: getAnalytics($entrepreneur, 'reveal', 7),
                'month' => self::getAnalytics($entrepreneur, 'reveal', 30),
            ],
        ];
    }

    static private function getAnalytics($entrepreneur, string $type, int $days)
    {
        $today = Carbon::today();

        $array = [];

        $businessIds = (new EntrepreneurRepository())->getOwnedBusinessIds($entrepreneur->id);

        switch ($type) {
            case 'bookmark':
                {
                    $query = UserBookmark::query();
                    break;
                }
            case 'view':
                {
                    $query = InvestorView::withTrashed();
                    break;
                }
            case 'reveal':
                {
                    $query = InvestorReveal::query();
                    break;
                }
        }

        for ($i = 0; $i < $days; $i++) {

            $date = $today->toDate();

            $count = $query->whereIn('business_id', $businessIds)
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
