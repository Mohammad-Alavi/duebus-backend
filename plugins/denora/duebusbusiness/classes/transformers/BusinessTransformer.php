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
            'logo'                               => [
                'id' => $business->logo->id,
                'disk_name' => $business->logo->disk_name,
                'extension' => $business->logo->extension,
                'path' => $business->logo->path,
            ],
            'name'                               => $business->name,
            'industry'                           => $business->industry,
            'year_founded'                       => (int)$business->year_founded,
            'website'                            => $business->website,
            'allow_reveal'                       => (bool)$business->allow_reveal,
            'existing_business'                  => (bool)$business->existing_business,
            'legal_structure'                    => $business->legal_structure,
            'your_role_in_business'              => $business->your_role_in_business,
            'reason_of_selling_equity'           => $business->reason_of_selling_equity,
            'business_value'                     => (float)$business->business_value,
            'equity_for_sale'                    => (float)$business->equity_for_sale,
            'is_involved_in_any_proceedings'     => (bool)$business->is_involved_in_any_proceedings,
            'is_concern_with_business_employees' => (bool)$business->is_concern_with_business_employees,
            'is_founder_or_holder_in_debt'       => (bool)$business->is_founder_or_holder_in_debt,

            'created_at' => $business->created_at,
            'updated_at' => $business->updated_at,
        ];

    }
}