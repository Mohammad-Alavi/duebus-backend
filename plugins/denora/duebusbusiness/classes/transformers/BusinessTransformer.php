<?php namespace Denora\Duebusbusiness\Classes\Transformers;

use Denora\Duebusbusiness\Models\Business;
use RainLab\User\Facades\Auth;

class BusinessTransformer {

    /**
     * @param Business $business
     *
     * @return array
     */
    static function transform($business) {
        $user = Auth::user();
        $isOwned = $user->id == $business->entrepreneur->user->id;
        $isViewed = $user->investor?$user->investor->viewed_businesses->contains($business->id):false;
        $isRevealed = $user->investor?$user->investor->revealed_businesses->contains($business->id):false;

        $isBookmarked = $user->bookmarked_businesses->contains($business->id);

        return [
            'id'                                 => $business->id,
            'logo'                               => $business->logo ? $business->logo->path : null,
            'name'                               => $business->name,
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
            'is_concern_with_business_employees' => $business->is_concern_with_business_employees,
            'is_founder_or_holder_in_debt'       => $business->is_founder_or_holder_in_debt,

            'is_published' => $business->is_published,
            'paid_at' => $business->paid_at,

            'three_years_statement' => json_decode($business->three_years_statement),

            'social_media' => json_decode($business->social_media),

            'equity_holders' => json_decode($business->equity_holders),

            'is_viewed' => $isOwned || $isViewed,
            'is_revealed' => $isOwned || $isRevealed,
            'is_bookmarked' => $isBookmarked,

            'bookmarked_count' => count($business->bookmarked_users),

            'created_at' => $business->created_at,
            'updated_at' => $business->updated_at,
        ];

    }
}
