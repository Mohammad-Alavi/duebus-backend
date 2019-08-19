<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Models\Package;

class PackageTransformer {

    /**
     * @param Package $package
     *
     * @return array
     */
    static function transform($package) {

        return [
            'id'          => $package->id,
            'name'        => $package->name,
            'description' => $package->description,
            'amount'      => $package->amount,
            'offer'       => $package->offer,
            'price'       => $package->price,
            'points'      => $package->points,
            'recommended' => (bool)$package->recommended,

            'created_at' => $package->created_at,
            'updated_at' => $package->updated_at,
        ];

    }
}