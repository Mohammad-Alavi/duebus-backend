<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Models\Experience;

class ExperiencesTransformer {

    /**
     * @param Experience[] $experiences
     *
     * @return array
     */
    static function transform($experiences) {
        $array = [];

        if ($experiences == null) return $array;

        foreach ($experiences as $experience) {
            array_push($array, ExperienceTransformer::transform($experience));
        }

        return $array;
    }

}