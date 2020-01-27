<?php namespace Denora\Duebusverification\Classes\Repositories;

use Denora\Duebusverification\Models\ChamberOfCommerce;

class ChamberOfCommerceRepository
{

    /**
     * @param int $id
     *
     * @return mixed
     */
    static public function findById(int $id)
    {
        return ChamberOfCommerce::find($id);
    }

    /**
     * @param $businessVerification
     *
     * @return mixed
     */
    static public function createChamberOfCommerce(
        $businessVerification
    )
    {
        $chamberOfCommerce = new ChamberOfCommerce();
        $businessVerification->chamber_of_commerce()->save($chamberOfCommerce);

        //  Create an Item(details) and attach to this model
        ItemRepository::createItem($chamberOfCommerce);

        return $businessVerification;
    }

    /**
     * @param $businessVerification
     * @param $image
     * @param $description
     *
     * @return mixed
     */
    static public function updateChamberOfCommerce($businessVerification, $image, $description)
    {
        $chamberOfCommerce = $businessVerification->chamber_of_commerce;
        $chamberOfCommerce->image = $image;
        $businessVerification->chamber_of_commerce()->save($chamberOfCommerce);
        ItemRepository::updateItem($chamberOfCommerce, $description);

        return $businessVerification;
    }
}
