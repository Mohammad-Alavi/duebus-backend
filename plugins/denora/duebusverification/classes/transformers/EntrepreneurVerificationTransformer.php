<?php namespace Denora\Duebusverification\Classes\Transformers;

use Denora\Duebusverification\Models\EntrepreneurVerification;

class EntrepreneurVerificationTransformer
{

    /**
     * @param EntrepreneurVerification $entrepreneurVerification
     *
     * @return array
     */
    static function transform(EntrepreneurVerification $entrepreneurVerification)
    {
        return [
            //'id' => $entrepreneurVerification->id,

            'id_of_managing_partner' => ItemableTransformer::transform($entrepreneurVerification->id_of_managing_partner),
            'article_of_association' => ItemableTransformer::transform($entrepreneurVerification->article_of_association),
            'commercial_license' => ItemableTransformer::transform($entrepreneurVerification->commercial_license),
            'chamber_of_commerce' => ItemableTransformer::transform($entrepreneurVerification->chamber_of_commerce),

            //'created_at' => $entrepreneurVerification->created_at,
            //'updated_at' => $entrepreneurVerification->updated_at,
        ];
    }

}
