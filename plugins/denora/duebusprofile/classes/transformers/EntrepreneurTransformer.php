<?php namespace Denora\Duebusprofile\Classes\Transformers;

use Denora\Duebusprofile\Models\Entrepreneur;
use Denora\Duebusverification\Classes\Transformers\EntrepreneurVerificationTransformer;

class EntrepreneurTransformer {

    /**
     * @param Entrepreneur $entrepreneur
     *
     * @return array
     */
    static function transform(Entrepreneur $entrepreneur) {
        return [
            'id'          => $entrepreneur->id,
            'educations'  => $entrepreneur->educations ?json_decode($entrepreneur->educations): [],
            'experiences' => $entrepreneur->experiences ?json_decode($entrepreneur->experiences): [],

            'is_verified'  => $entrepreneur->verification->is_verified,
            'verification' => EntrepreneurVerificationTransformer::transform($entrepreneur->verification),

            //'created_at' => $entrepreneur->created_at,
            //'updated_at' => $entrepreneur->updated_at,
        ];
    }

}
