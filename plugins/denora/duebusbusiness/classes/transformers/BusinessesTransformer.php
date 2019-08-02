<?php namespace Denora\Duebusbusiness\Classes\Transformers;

use Denora\Duebusbusiness\Models\Business;

class BusinessesTransformer {

    /**
     * @param Business[] $businesses
     *
     * @return array
     */
    static function transform($businesses) {
        $array = [];

        if ($businesses == null) return $array;

        foreach ($businesses as $business) {
            array_push($array, BusinessTransformer::transform($business));
        }

        return $array;
    }

}