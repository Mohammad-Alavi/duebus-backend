<?php namespace Denora\Duebusprofile\Classes\Transformers;

use Denora\Duebus\Classes\Transformers\SectorsTransformer;
use Denora\Duebusprofile\Models\Investor;

class InvestorTransformer {

    /**
     * @param Investor $investor
     *
     * @return array
     */
    static function transform(Investor $investor) {
        return [
            'id'                              => $investor->id,
            'range_of_investment'             => $investor->range_of_investment,
            'range_of_businesses_invested_in' => $investor->range_of_businesses_invested_in,
            'sectors'                         => SectorsTransformer::transform($investor->sectors),

            'created_at' => $investor->created_at,
            'updated_at' => $investor->updated_at,
        ];
    }

}