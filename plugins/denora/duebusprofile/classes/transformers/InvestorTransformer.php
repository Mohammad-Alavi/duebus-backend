<?php namespace Denora\Duebusprofile\Classes\Transformers;

use Denora\Duebus\Classes\Transformers\SectorsTransformer;
use Denora\Duebusprofile\Models\Investor;
use Denora\Duebusverification\Classes\Transformers\InvestorVerificationTransformer;

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
            'invested_on_duebus'              => $investor->invested_on_duebus,
            'sectors'                         => SectorsTransformer::transform($investor->sectors) ? SectorsTransformer::transform($investor->sectors) : null,

            'viewed_businesses_count' => count($investor->viewed_businesses),
            'revealed_businesses_count' => count($investor->revealed_businesses),
            'is_verified'  => $investor->verification->is_verified,
            'verification' => InvestorVerificationTransformer::transform($investor->verification),

            //'created_at' => $investor->created_at,
            //'updated_at' => $investor->updated_at,
        ];
    }

}
