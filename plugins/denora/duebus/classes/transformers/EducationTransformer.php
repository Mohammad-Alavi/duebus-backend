<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Models\Education;

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