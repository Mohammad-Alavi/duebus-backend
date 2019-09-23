<?php namespace Denora\Duebusprofile\Classes\Transformers;

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

            'number_of_clients' => $representative->number_of_clients,
            'interested_in' => $representative->interested_in,

            'created_at' => $representative->created_at,
            'updated_at' => $representative->updated_at,
        ];
    }

}