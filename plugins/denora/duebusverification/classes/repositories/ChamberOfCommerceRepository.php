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
     * @param $entrepreneurVerification
     *
     * @return mixed
     */
    static public function createChamberOfCommerce(
        $entrepreneurVerification
    )
    {
        $chamberOfCommerce = new ChamberOfCommerce();
        $entrepreneurVerification->chamber_of_commerce()->save($chamberOfCommerce);

        //  Create an Item(details) and attach to this model
        ItemRepository::createItem($chamberOfCommerce);

        return $entrepreneurVerification;
    }

    /**
     * @param $entrepreneurVerification
     * @param $image
     * @param $description
     *
     * @return mixed
     */
    static public function updateChamberOfCommerce($entrepreneurVerification, $image, $description)
    {
        $chamberOfCommerce = $entrepreneurVerification->chamber_of_commerce;
        $chamberOfCommerce->image = $image;
        $entrepreneurVerification->chamber_of_commerce()->save($chamberOfCommerce);
        ItemRepository::updateItem($chamberOfCommerce, $description);

        return $entrepreneurVerification;
    }
}
