<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Models\Entrepreneur;

class EntrepreneurTransformer {

    /**
     * @param Entrepreneur $entrepreneur
     *
     * @return array
     */
    static function transform(Entrepreneur $entrepreneur) {
        return [
            'id' => $entrepreneur->id,
            'educations'  => EducationsTransformer::transform($entrepreneur->educations),
            'experiences' => ExperiencesTransformer::transform($entrepreneur->experiences),

            'created_at' => $entrepreneur->created_at,
            'updated_at' => $entrepreneur->updated_at,
        ];
    }

}