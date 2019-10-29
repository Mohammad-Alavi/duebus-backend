<?php namespace Denora\Duebusverification\Classes\Transformers;

class ItemableTransformer {

    /**
     * @param $itemable
     * @return array
     */
    static function transform($itemable) {
        return [
            //'id' => $itemable->id,

            'image'       => $itemable->image ? $itemable->image->path : null,
            'description' => $itemable->details->description,
            'is_verified' => $itemable->details->is_verified === null ? null : (boolean)$itemable->details->is_verified,
            //'details' => ItemTransformer::transform($itemable->details),

            //'created_at' => $itemable->created_at,
            //'updated_at' => $itemable->updated_at,
        ];
    }

}
