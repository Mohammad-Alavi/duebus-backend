<?php namespace Denora\Duebusverification\Classes\Transformers;

use Denora\Duebusverification\Models\Passport;

class PassportTransformer {

    /**
     * @param Passport $passport
     *
     * @return array
     */
    static function transform(Passport $passport) {
        return [
            //'id' => $passport->id,

            'image'       => $passport->image ? $passport->image->path : null,
            'description' => $passport->details->description,
            'is_verified' => $passport->details->is_verified === null ? null : (boolean)$passport->details->is_verified,
            //'details' => ItemTransformer::transform($passport->details),

            //'created_at' => $passport->created_at,
            //'updated_at' => $passport->updated_at,
        ];
    }

}
