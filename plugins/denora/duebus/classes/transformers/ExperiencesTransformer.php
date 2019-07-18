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
            array_push($array, [
                'id'        => $experience->id,
                'company'   => $experience->company,
                'job_title' => $experience->job_title,
                'from'      => $experience->from,
                'to'        => $experience->to,

                'created_at' => $experience->created_at,
                'updated_at' => $experience->updated_at,
            ]);
        }

        return $array;
    }

}