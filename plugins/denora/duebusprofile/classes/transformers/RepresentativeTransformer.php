<?php namespace Denora\Duebusprofile\Classes\Transformers;

use Denora\Duebus\Classes\Transformers\SectorsTransformer;
use Denora\Duebusprofile\Models\Representative;

class RepresentativeTransformer {

    /**
     * @param Representative $representative
     *
     * @return array
     */
    static function transform(Representative $representative) {
        return [
            'id' => $representative->id,

            'number_of_clients'   => $representative->number_of_clients,
            'interested_in'       => $representative->interested_in,
            'range_of_investment' => $representative->range_of_investment,
            'sectors'             => SectorsTransformer::transform($representative->sectors) ? SectorsTransformer::transform($representative->sectors) : null,
            'business_name'       => $representative->business_name,
            'year_founded'        => $representative->year_founded,
            'website'             => $representative->website,

            'social_media' => json_decode($representative->social_media),

            'created_at' => $representative->created_at,
            'updated_at' => $representative->updated_at,
        ];
    }

}