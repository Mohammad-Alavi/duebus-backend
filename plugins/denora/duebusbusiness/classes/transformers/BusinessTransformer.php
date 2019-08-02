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
            'id'   => $business->id,
            'name' => $business->name,

            'created_at' => $business->created_at,
            'updated_at' => $business->updated_at,
        ];

    }
}