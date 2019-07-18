<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Models\Education;

class EducationsTransformer {

    /**
     * @param Education[] $educations
     *
     * @return array
     */
    static function transform($educations) {
        $array = [];

        if ($educations == null) return $array;

        foreach ($educations as $education) {
            array_push($array, [
                'id'             => $education->id,
                'school'         => $education->school,
                'field_of_study' => $education->field_of_study,
                'from'           => $education->from,
                'to'             => $education->to,

                'created_at' => $education->created_at,
                'updated_at' => $education->updated_at,
            ]);
        }

        return $array;
    }

}