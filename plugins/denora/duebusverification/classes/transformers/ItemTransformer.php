<?php namespace Denora\Duebusverification\Classes\Transformers;

use Denora\Duebusverification\Models\Item;

class ItemTransformer {

    /**
     * @param Item $item
     *
     * @return array
     */
    static function transform(Item $item) {
        return [
            'description' => $item->description,
            'is_verified' => $item->is_verified === null ? null : (boolean)$item->is_verified,
        ];
    }

}