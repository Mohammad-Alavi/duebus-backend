<?php namespace Denora\Duebusbusiness\Classes\Transformers;

use Denora\Duebusbusiness\Models\Business;

class BusinessTransformer {

    /**
     * @param Business $business
     *
     * @return array
     */
    static function transform($business) {

        return [
            'id'                                 => $business->id,
            'name'                               => $business->name,
            'industry'                           => $business->industry,
            'year_founded'                       => $business->year_founded,
            'website'                            => $business->website,
            'allow_reveal'                       => $business->allow_reveal,
            'existing_business'                  => $business->existing_business,
            'your_role_in_business'              => $business->your_role_in_business,
            'reason_of_selling_equity'           => $business->reason_of_selling_equity,
            'business_value'                     => $business->business_value,
            'equity_for_sale'                    => $business->equity_for_sale,
            'is_involved_in_any_proceedings'     => $business->is_involved_in_any_proceedings,
            'is_concern_with_business_employees' => $business->is_concern_with_business_employees,
            'is_founder_or_holder_in_debt'       => $business->is_founder_or_holder_in_debt,

            'created_at' => $business->created_at,
            'updated_at' => $business->updated_at,
        ];

    }
}