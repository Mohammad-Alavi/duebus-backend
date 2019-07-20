<?php namespace Denora\Duebusprofile\Classes\Transformers;

use Denora\Duebusprofile\Models\Education;

class EducationTransformer {

    /**
     * @param Education $education
     *
     * @return array
     */
    static function transform($education) {

        return [
            'id'             => $education->id,
            'school'         => $education->school,
            'field_of_study' => $education->field_of_study,
            'from'           => $education->from,
            'to'             => $education->to,

            'created_at' => $education->created_at,
            'updated_at' => $education->updated_at,
        ];

    }
}