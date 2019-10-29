<?php namespace Denora\Duebusverification\Classes\Repositories;

use Denora\Duebusverification\Models\Item;

class ItemRepository {

    /**
     * @param int $itemId
     *
     * @return mixed
     */
    static public function findById(int $itemId) {
        return Item::find($itemId);
    }

    /**
     * @param $itemable
     *
     * @return mixed
     */
    static public function createItem($itemable) {
        $item = new Item();
        $itemable->details()->save($item);

        return $itemable;
    }

    /**
     * @param $itemable
     * @param $item
     * @param $description
     *
     * @return mixed
     */
    static public function updateItem($itemable, $description) {
        $item = $itemable->details;
        $item->description = $description;
        $item->is_verified = null;
        $itemable->details()->save($item);

        return $itemable;
    }


}
