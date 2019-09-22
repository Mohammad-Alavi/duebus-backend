<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Models\Sector;

class SectorTransformer {

    /**
     * @param Sector $sector
     *
     * @return array
     */
    static function transform($sector) {

        return [
            'id'    => $sector->id,
            'label' => $sector->label,
        ];

    }
}