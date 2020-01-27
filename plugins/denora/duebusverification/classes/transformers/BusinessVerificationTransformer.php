<?php namespace Denora\Duebusverification\Classes\Transformers;

use Denora\Duebusverification\Models\BusinessVerification;

class BusinessVerificationTransformer
{

    /**
     * @param BusinessVerification $businessVerification
     *
     * @return array
     */
    static function transform(BusinessVerification $businessVerification)
    {
        return [
            //'id' => $businessVerification->id,

            'id_of_managing_partner' => ItemableTransformer::transform($businessVerification->id_of_managing_partner),
            'article_of_association' => ItemableTransformer::transform($businessVerification->article_of_association),
            'commercial_license' => ItemableTransformer::transform($businessVerification->commercial_license),
            'chamber_of_commerce' => ItemableTransformer::transform($businessVerification->chamber_of_commerce),

            //'created_at' => $businessVerification->created_at,
            //'updated_at' => $businessVerification->updated_at,
        ];
    }

}
