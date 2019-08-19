<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Models\Package;

class PackagesTransformer {

    /**
     * @param Package[] $packages
     *
     * @return array
     */
    static function transform($packages) {
        $array = [];

        if ($packages == null) return $array;

        foreach ($packages as $package) {
            array_push($array, PackageTransformer::transform($package));
        }

        return $array;
    }

}