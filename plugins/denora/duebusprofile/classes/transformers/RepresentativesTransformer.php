<?php namespace Denora\Duebusprofile\Classes\Transformers;

use Denora\Duebusprofile\Models\Representative;

class RepresentativesTransformer
{

    /**
     * @param Representative[] $representatives
     *
     * @return array
     */
    static function transform($representatives)
    {
        $array = [];

        if ($representatives == null) return $array;

        foreach ($representatives as $representative) {
            array_push($array, RepresentativeTransformer::transform($representative));
        }

        return $array;
    }

}
