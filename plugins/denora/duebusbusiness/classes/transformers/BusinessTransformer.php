<?php namespace Denora\Duebusbusiness\Classes\Transformers;

use Denora\Duebusbusiness\Classes\Repositories\BusinessRepository;
use Denora\Duebusbusiness\Models\Business;
use Denora\Duebusprofile\Classes\Transformers\EntrepreneurTransformer;
use Denora\Duebusprofile\Models\InvestorView;
use Denora\Duebusverification\Classes\Transformers\BusinessVerificationTransformer;
use Illuminate\Support\Carbon;
use RainLab\User\Facades\Auth;

class BusinessTransformer {

    /**
     * @param Business $business
     *
     * @return array
     */
    static function transform($business) {
        BusinessRepository::removeExpiredViewed();
        $user = Auth::user();
        $isOwned = ($user)? $user->id == $business->entrepreneur->user->id: false;
        $isViewed = ($user && $user->investor)? BusinessRepository::isBusinessViewed($user->investor, $business->id): false;
        $viewExpiresAt = $isViewed?
            Carbon::createFromTimeString(InvestorView::query()->where('investor_id', $user->investor->id)->where('business_id', $business->id)->first()['created_at'])->addHours(2)
            :null;
        $isRevealed = ($user && $user->investor)? $user->investor->revealed_businesses->contains($business->id): false;

        $isBookmarked = ($user)? $user->bookmarked_businesses->contains($business->id): false;

        return [
            'id'                                 => $business->id,
            'logo'                               => $business->logo ? $business->logo->path : null,
            'name'                               => $business->name,
            'business_brief'                     => $business->business_brief,
            'industry'                           => $business->industry,
            'year_founded'                       => $business->year_founded,
            'website'                            => $business->website,
            'allow_reveal'                       => $business->allow_reveal,
            'existing_business'                  => $business->existing_business,
            'legal_structure'                    => $business->legal_structure,
            'your_role_in_business'              => $business->your_role_in_business,
            'reason_of_selling_equity'           => $business->reason_of_selling_equity,
            'business_value'                     => $business->business_value,
            'equity_for_sale'                    => $business->equity_for_sale,
            'asking_price'                       => $business->asking_price,
            'is_involved_in_any_proceedings'     => $business->is_involved_in_any_proceedings,
            'is_involved_in_any_proceedings_description'     => $business->is_involved_in_any_proceedings_description,
            'is_concern_with_business_employees' => $business->is_concern_with_business_employees,
            'is_concern_with_business_employees_description' => $business->is_concern_with_business_employees_description,
            'is_founder_or_holder_in_debt'       => $business->is_founder_or_holder_in_debt,
            'is_founder_or_holder_in_debt_description'       => $business->is_founder_or_holder_in_debt_description,

            'is_published' => $business->is_published,
            'paid_at' => $business->paid_at,

            'three_years_statement' => json_decode($business->three_years_statement),

            'social_media' => json_decode($business->social_media),

            'equity_holders' => json_decode($business->equity_holders),

            'is_viewed' => $isOwned || $isViewed,
            'view_expires_in_seconds' => $viewExpiresAt? Carbon::now()->diffInSeconds($viewExpiresAt) :null,
            'is_revealed' => $isOwned || $isRevealed,
            'is_bookmarked' => $isBookmarked,

            'bookmarked_count' => count($business->bookmarked_users),

            'completion_in_percent' => BusinessRepository::getPercentCompletion($business),

            'is_promoted' => Carbon::now()->lt($business->promotion_expire_date),
            'promotion_industry' => $business->promotion_industry,
            'promotion_expire_date' => $business->promotion_expire_date,
            'promotion_expires_in_seconds' => self::getTimeDifferenceTillNowInSeconds($business->promotion_expire_date),

            'entrepreneur' => EntrepreneurTransformer::transform($business->entrepreneur),

            'verification' => BusinessVerificationTransformer::transform($business->verification),
            'is_verified'  => $business->verification->is_verified,

            'created_at' => $business->created_at,
            'updated_at' => $business->updated_at,
        ];

    }

    private static function getTimeDifferenceTillNowInSeconds($dateTime): int {
        if (!$dateTime) return 0;

        $dateTime = Carbon::createFromTimeString($dateTime);

        if (Carbon::now()->gt($dateTime)) return 0;

        return Carbon::now()->diffInSeconds($dateTime);
    }
}
