<?php namespace Denora\Duebusprofile\Classes\Transformers;

use Denora\Duebusprofile\Models\Experience;

class ExperienceTransformer {

    /**
     * @param Experience $experience
     *
     * @return array
     */
    static function transform($experience) {
        return [
            'id'        => $experience->id,
            'company'   => $experience->company,
            'job_title' => $experience->job_title,
            'from'      => $experience->from,
            'to'        => $experience->to,

            'created_at' => $experience->created_at,
            'updated_at' => $experience->updated_at,
        ];
    }

}