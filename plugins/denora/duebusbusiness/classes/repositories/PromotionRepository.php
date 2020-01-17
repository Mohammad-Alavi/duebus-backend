<?php namespace Denora\Duebusbusiness\Classes\Repositories;

use Denora\Duebusbusiness\Models\Business;
use Illuminate\Support\Carbon;

class PromotionRepository
{
    static public function getPromotionsCount($industry = null)
    {
        return count(self::getAllPromotions($industry));
    }

    static public function paginate(int $page, $industry = null, $existing = null)
    {
        $query = Business::query();
        $query->whereDate('promotion_expire_date', '>', Carbon::now());
        $query->where('promotion_industry', $industry);
        if ($existing !== null)
            $query->where('existing_business', $existing);

        return $query->paginate(10, $page);
    }

    static private function getAllPromotions($industry = null)
    {
        $query = Business::query();
        $query->whereDate('promotion_expire_date', '>', Carbon::now());
        $query->where('promotion_industry', $industry);

        return $query->get();
    }

    static public function promote(int $businessId, $industry = null)
    {
        $business = (new BusinessRepository())->findById($businessId);
        $business->promotion_industry = $industry;
        $business->promotion_expire_date = Carbon::now()->addWeek(1);
        $business->save();
    }

//    private static function removeExpiredPromotions(){
//        $query = Business::query();
//        $query->whereDate('promotion_expire_date', '<=', Carbon::now());
//        $query->each(function ($business){
//            $business->promotion_industry = null;
//            $business->promotion_expire_date = null;
//            $business->save();
//        });
//    }
}
