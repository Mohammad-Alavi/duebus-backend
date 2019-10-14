<?php namespace Denora\Duebusverification\Classes\Transformers;

use Denora\Duebusverification\Models\Identification;

class IdentificationTransformer {

    /**
     * @param Identification $identification
     *
     * @return array
     */
    static function transform(Identification $identification) {
        return [
            //'id' => $identification->id,

            'image'       => $identification->image ? $identification->image->path : null,
            'description' => $identification->details->description,
            'is_verified' => $identification->details->is_verified === null ? null : (boolean)$identification->details->is_verified,
            //'details' => ItemTransformer::transform($identification->details),

            //'created_at' => $identification->created_at,
            //'updated_at' => $identification->updated_at,
        ];
    }

}
