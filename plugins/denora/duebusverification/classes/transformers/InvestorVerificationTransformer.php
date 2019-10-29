<?php namespace Denora\Duebusverification\Classes\Transformers;

use Denora\Duebusverification\Models\InvestorVerification;

class InvestorVerificationTransformer {

    /**
     * @param InvestorVerification $investorVerification
     *
     * @return array
     */
    static function transform(InvestorVerification $investorVerification) {
        return [
            //'id' => $investorVerification->id,

            'passport'       => ItemableTransformer::transform($investorVerification->passport),
            'identification' => ItemableTransformer::transform($investorVerification->identification),

            //'created_at' => $investorVerification->created_at,
            //'updated_at' => $investorVerification->updated_at,
        ];
    }

}
