<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Models\Sector;

class SectorsTransformer {

    /**
     * @param Sector[] $sectors
     *
     * @return array
     */
    static function transform($sectors) {
        $array = [];

        if ($sectors == null) return $array;

        foreach ($sectors as $sector) {
            array_push($array, SectorTransformer::transform($sector));
        }

        return $array;
    }

}